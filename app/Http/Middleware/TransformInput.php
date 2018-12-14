<?php

namespace App\Http\Middleware;

use App\Models\Tenant\Catalogs\UnitType;
use App\Models\Tenant\Company;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Document;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Item;
use Closure;
use Exception;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->replace($this->originalAttribute($request->all()));
        return $next($request);
    }

    private function originalAttribute($inputs)
    {
        try {

            $items = [];
            foreach ($inputs['items'] as $row)
            {
                $attributes = [];
                if(array_key_exists('datos_adicionales', $row)) {
                    foreach ($row['datos_adicionales'] as $add)
                    {
                        $attributes[] = [
                            'code' => $add['codigo'],
                            'name' => $add['nombre'],
                            'value' => array_key_exists('valor', $add)?$add['valor']:null,
                            'start_date' => array_key_exists('fecha_inicio', $add)?$add['fecha_inicio']:null,
                            'end_date' => array_key_exists('fecha_fin', $add)?$add['fecha_fin']:null,
                            'duration' => array_key_exists('duracion', $add)?$add['duracion']:null,
                        ];
                    }
                }

                $charges = $this->getChargesDiscounts($row, 'cargos');
                $discounts = $this->getChargesDiscounts($row, 'descuentos');

                $item = [
                    'internal_id' => array_key_exists('codigo_interno', $row)?$row['codigo_interno']:null,
                    'description' => $row['descripcion'],
                    'item_code' => array_key_exists('codigo_producto_sunat', $row)?$row['codigo_producto_sunat']:null,
                    'item_code_gs1' => array_key_exists('codigo_producto_gsl', $row)?$row['codigo_producto_gsl']:null,
                    'unit_type_id' => strtoupper($row['unidad_de_medida']),
                    'unit_price' => $row['precio_unitario'],
                ];

                $items[] = [
                    'item_id' => $this->itemFirstOrCreate($item),
                    'internal_id' => $item['internal_id'],
                    'item_description' => $item['description'],
                    'item_code' => $item['item_code'],
                    'item_code_gs1' => $item['item_code_gs1'],
                    'unit_type_id' => $item['unit_type_id'],
                    'quantity' => $row['cantidad'],
                    'unit_value' => $row['valor_unitario'],
                    'price_type_id' => $row['codigo_tipo_precio'],
                    'unit_price' => $row['precio_unitario'],

                    'affectation_igv_type_id' => $row['codigo_tipo_afectacion_igv'],
                    'total_base_igv' => $row['total_base_igv'],
                    'percentage_igv' => $row['porcentaje_igv'],
                    'total_igv' => $row['total_igv'],

                    'system_isc_type_id' => array_key_exists('codigo_tipo_sistema_isc', $row)?$row['codigo_tipo_sistema_isc']:null,
                    'total_base_isc' => array_key_exists('total_base_isc', $row)?$row['total_base_isc']:0,
                    'percentage_isc' => array_key_exists('porcentaje_isc', $row)?$row['porcentaje_isc']:0,
                    'total_isc' => array_key_exists('total_isc', $row)?$row['total_isc']:0,

                    'total_base_other_taxes' => array_key_exists('total_base_otros_impuestos', $row)?$row['total_base_otros_impuestos']:0,
                    'percentage_other_taxes' => array_key_exists('porcentaje_otros_impuestos', $row)?$row['porcentaje_otros_impuestos']:0,
                    'total_other_taxes' => array_key_exists('total_otros_impuestos', $row)?$row['total_otros_impuestos']:0,

                    'total_taxes' => $row['total_impuestos'],
                    'total_value' => $row['total_valor_item'],
                    'total' => $row['total_item'],

                    'charges' => $charges,
                    'discounts' => $discounts,
                    'attributes' => $attributes,
                ];
            }

            $prepayments = [];
            if (array_key_exists('anticipos', $inputs)) {
                foreach ($inputs['anticipos'] as $row)
                {
                    $prepayments[] = [
                        'number' => $row['numero'],
                        'document_type_id' => $row['codigo_tipo_documento'],
                        'amount' => $row['monto'],
                    ];
                }
            }

            $related_documents = null;
            if (array_key_exists('documentos_relacionados', $inputs)) {
                foreach ($inputs['documentos_relacionados'] as $row)
                {
                    $prepayments[] = [
                        'number' => $row['numero'],
                        'document_type_id' => $row['codigo_tipo_documento']
                    ];
                }
            }

            $perception = null;
            if(array_key_exists('percepcion', $inputs)) {
                $data = $inputs['percepcion'];
                $perception = [
                    'code' => $data['codigo'],
                    'percentage' => $data['porcentaje'],
                    'amount' => $data['monto'],
                    'base' => $data['base'],
                ];
            }

            $detraction = null;
            if(array_key_exists('detraccion', $inputs)) {
                $data = $inputs['detraccion'];
                $detraction = [
                    'payment_method_id' => $data['codigo_metodo_pago'],
                    'bank_account' => $data['cuenta_bancaria'],
                    'detraction_type_id' => $data['codigo_tipo_detraccion'],
                    'percentage' => $data['porcentaje'],
                    'amount' => $data['monto'],
                ];
            }

            $optional = [];
            if(array_key_exists('extras', $inputs)) {
                $data = $inputs['extras'];
                $optional = [
                    'observations' => array_key_exists('observaciones', $data)?$data['observaciones']:null,
                    'method_payment' => array_key_exists('forma_de_pago', $data)?$data['forma_de_pago']:null,
                    'salesman' => array_key_exists('vendedor', $data)?$data['vendedor']:null,
                    'box_number' => array_key_exists('caja', $data)?$data['caja']:null ,
                    'format_pdf' => array_key_exists('formato_pdf', $data)?$data['formato_pdf']:'a4'
                ];
            }

            $charges = $this->getChargesDiscounts($inputs, 'cargos');
            $discounts = $this->getChargesDiscounts($inputs, 'descuentos');

            //  Total Variables
            $total_other_charges = array_key_exists('total_otros_cargos', $inputs['totales'])?$inputs['totales']['total_otros_cargos']:0;
            $total_exportation = array_key_exists('total_exportacion', $inputs['totales'])?$inputs['totales']['total_exportacion']:0;
            $total_taxed = array_key_exists('total_operaciones_gravadas', $inputs['totales'])?$inputs['totales']['total_operaciones_gravadas']:0;
            $total_unaffected = array_key_exists('total_operaciones_inafectas', $inputs['totales'])?$inputs['totales']['total_operaciones_inafectas']:0;
            $total_exonerated = array_key_exists('total_operaciones_exoneradas', $inputs['totales'])?$inputs['totales']['total_operaciones_exoneradas']:0;
            $total_igv = array_key_exists('total_igv', $inputs['totales'])?$inputs['totales']['total_igv']:0;
            $total_base_isc = array_key_exists('total_base_isc', $inputs['totales'])?$inputs['totales']['total_base_isc']:0;
            $total_isc = array_key_exists('total_isc', $inputs['totales'])?$inputs['totales']['total_isc']:0;
            $total_base_other_taxes = array_key_exists('total_base_otros_impuestos', $inputs['totales'])?$inputs['totales']['total_base_otros_impuestos']:0;
            $total_other_taxes = array_key_exists('total_otros_impuestos', $inputs['totales'])?$inputs['totales']['total_otros_impuestos']:0;
            $total_taxes = array_key_exists('total_impuestos', $inputs['totales'])?$inputs['totales']['total_impuestos']:0;
            $total_free = array_key_exists('total_operaciones_gratuitas', $inputs['totales'])?$inputs['totales']['total_operaciones_gratuitas']:0;
            $total_discount = array_key_exists('total_descuentos', $inputs['totales'])?$inputs['totales']['total_descuentos']:0;
            $total_charge = array_key_exists('total_cargos', $inputs['totales'])?$inputs['totales']['total_cargos']:0;
            $total_prepayment = array_key_exists('total_anticipos', $inputs['totales'])?$inputs['totales']['total_anticipos']:0;
            $total_value = array_key_exists('total_valor', $inputs['totales'])?$inputs['totales']['total_valor']:0;
            $total = $inputs['totales']['total_venta'];

            // Date Variables
            $date_of_issue = $inputs['fecha_de_emision'];
            $time_of_issue = $inputs['hora_de_emision'];
            $date_of_due = array_key_exists('fecha_de_vencimiento', $inputs)?$inputs['fecha_de_vencimiento']:null;

            // Document Variables
            $document_type_id = $inputs['codigo_tipo_documento'];

            if(!in_array($document_type_id, ['01', '03', '07', '08'])) {
                return [
                    'success' => false,
                    'message' => 'El código del tipo de documento es incorrecto.'
                ];
            }

            $ubl_version = "2.1";
            $currency_type_id = $inputs['codigo_tipo_moneda'];
            $document_series = $inputs['serie_documento'];
            $document_number = $inputs['numero_documento'];

            $doc = Document::where('document_type_id', $document_type_id)
                ->where('series', $document_series)
                ->where('number', $document_number)
                ->first();

            if($doc) {
                return [
                    'success' => false,
                    'message' => 'El documento ya se encuentra registrado.'
                ];
            }

            $purchase_order = array_key_exists('numero_orden_de_compra', $inputs)?$inputs['numero_orden_de_compra']:null;

            // Establishment
            $data = $inputs['datos_del_emisor'];
            $establishment = [
                'code' => $data['codigo_del_domicilio_fiscal']
            ];

            // Customer
            $data = $inputs['datos_del_cliente_o_receptor'];
            $customer = [
                'identity_document_type_id' => $data['codigo_tipo_documento_identidad'],
                'number' => $data['numero_documento'],
                'name' => $data['apellidos_y_nombres_o_razon_social'],
                'trade_name' => array_key_exists('nombre_comercial', $data)?$data['nombre_comercial']:null,
                'country_id' => array_key_exists('codigo_pais', $data)?$data['codigo_pais']:null,
                'district_id' => array_key_exists('ubigeo', $data)?$data['ubigeo']:null,
                'address' => array_key_exists('direccion', $data)?$data['direccion']:null,
                'email' => array_key_exists('correo_electronico', $data)?$data['correo_electronico']:null,
                'telephone' => array_key_exists('telephone', $data)?$data['telefono']:null,
            ];

            $document_base = [];
            $group_id = null;
            // Invoice Variables
            $operation_type_id = array_key_exists('codigo_tipo_operacion', $inputs)?$inputs['codigo_tipo_operacion']:null;
            // Note Variables
            $affected_document_series = array_key_exists('serie_de_documento_afectado', $inputs)?$inputs['serie_de_documento_afectado']:null;
            $affected_document_number_ = array_key_exists('numero_de_documento_afectado', $inputs)?$inputs['numero_de_documento_afectado']:null;
            $affected_document_type_id = array_key_exists('tipo_de_documento_afectado', $inputs)?$inputs['tipo_de_documento_afectado']:null;
            $note_credit_or_debit_type_id = array_key_exists('codigo_de_tipo_de_la_nota', $inputs)?$inputs['tipo_de_operacion']:null;
            $description = array_key_exists('motivo_o_sustento_de_la_nota', $inputs)?$inputs['motivo_o_sustento_de_la_nota']:null;

            // Acciones
            $send_email = array_key_exists('enviar_email', $inputs)?(bool)$inputs['enviar_email']:false;

            /*
             * Invoice
             */
            if (in_array($document_type_id, ['01', '03'])) {
                $document_base = [
                    'operation_type_id' => $operation_type_id,
                    'date_of_due' => $date_of_due,
                    'total_free' => $total_free,
                    'total_discount' => $total_discount,
                    'total_charge' => $total_charge,
                    'total_prepayment' => $total_prepayment,
                    'total_value' => $total_value,

                    'charges' => $charges,
                    'discounts' => $discounts,
                    'perception' => $perception,
                    'detraction' => $detraction,
                    'prepayments' => $prepayments,
                ];
                $group_id = ($document_type_id === '01')?'01':'02';
            }

            /*
             * Note Credit, Note Debit
             */
            if (in_array($document_type_id, ['07', '08'])) {
                if ($document_type_id === '07') {
                    $note_type = 'credit';
                    $note_credit_type_id = $note_credit_or_debit_type_id;
                    $note_debit_type_id = null;
                } else {
                    $note_type = 'debit';
                    $note_credit_type_id = null;
                    $note_debit_type_id = $note_credit_or_debit_type_id;
                }

                $affected_document = Document::where('document_type_id', $affected_document_type_id)
                    ->where('series', $affected_document_series)
                    ->where('number', $affected_document_number_)
                    ->where('state_type_id', '05')
                    ->first();
                if ($affected_document) {
                    $document_base = [
                        'note_type' => $note_type,
                        'note_credit_type_id' => $note_credit_type_id,
                        'note_debit_type_id' => $note_debit_type_id,
                        'description' => $description,
                        'affected_document_id' => $affected_document->id,
                        'total_prepayment' => $total_prepayment,
                    ];
                    $group_id = ($affected_document_type_id === '01')?'01':'02';
                } else {
                    return [
                        'success' => false,
                        'message' => 'El documento afectado no se encuentra registrado, o no se encuentra aceptado.'
                    ];
                }
            }

            $guides = [];
            if (array_key_exists('guias', $inputs)) {
                foreach ($inputs['guias'] as $row)
                {
                    $guides[] = [
                        'number' => $row['numero'],
                        'document_type_id' => $row['tipo_de_documento'],
                    ];
                }
            }

            $legends = [];
            if (array_key_exists('leyendas', $inputs)) {
                foreach ($inputs['leyendas'] as $row)
                {
                    $legends[] = [
                        'code' => $row['codigo'],
                        'value' => $row['valor'],
                    ];
                }
            }

            $actions = [
                'send_email' => $send_email
            ];

            $original_attributes = [
                'document' => [
                    'user_id' => auth()->id(),
                    'external_id' => '',
                    'state_type_id' => '01',
                    'ubl_version' => $ubl_version,
                    'soap_type_id' => $this->getSoapType(),
                    'group_id' => $group_id,
                    'document_type_id' => $document_type_id,
                    'date_of_issue' => $date_of_issue,
                    'time_of_issue' => $time_of_issue,
                    'series' => $document_series,
                    'number' => $document_number,
                    'currency_type_id' => $currency_type_id,
                    'purchase_order' => $purchase_order,

                    'total_other_charges' => $total_other_charges,
                    'total_exportation' => $total_exportation,
                    'total_taxed' => $total_taxed,
                    'total_unaffected' => $total_unaffected,
                    'total_exonerated' => $total_exonerated,
                    'total_igv' => $total_igv,
                    'total_base_isc' => $total_base_isc,
                    'total_isc' => $total_isc,
                    'total_base_other_taxes' => $total_base_other_taxes,
                    'total_other_taxes' => $total_other_taxes,
                    'total_taxes' => $total_taxes,
                    'total' => $total,

                    'establishment_id' => $this->establishmentByCode($establishment),
                    'customer_id' => $this->customerFirstOrCreate($customer),
                    'legends' => $legends,
                    'guides' => $guides,
                    'related_documents' => $related_documents,
                    'optional' => $optional,

                    'items' => $items,
                    'filename' => '',
                    'hash' => '',
                    'qr' => '',
                ],
                'document_base' => $document_base,
                'actions' => $actions
            ];

            return $original_attributes;

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function getChargesDiscounts($inputs, $type)
    {
        $data = [];
        if(array_key_exists($type, $inputs)) {
            foreach ($inputs[$type] as $add)
            {
                $data[] = [
                    'code' => $add['codigo'],
                    'description' => $add['descripcion'],
                    'percentage' => $add['porcentaje'],
                    'amount' => $add['monto'],
                    'base' => $add['base'],
                ];
            }
        }

        return $data;
    }

    private function getSoapType()
    {
        $company = Company::first();
        return $company->soap_type_id;
    }

    private function establishmentByCode($data)
    {
        $establishment = Establishment::where('code', $data['code'])->first();
        return $establishment->id;
    }

    private function customerFirstOrCreate($data)
    {
        $department_id = null;
        $province_id = null;
        $district_id = $data['district_id'];

        if ($district_id) {
            $province_id = substr($district_id, 0 ,4);
            $department_id = substr($district_id, 0 ,2);
        }

        $customer = Customer::updateOrCreate(
            [
                'identity_document_type_id' => $data['identity_document_type_id'],
                'number' => $data['number']
            ],
            [
                'name' => $data['name'],
                'country_id' =>  $data['country_id'],
                'department_id' => $department_id,
                'province_id' => $province_id,
                'district_id' => $district_id,
                'address' => $data['address'],
                'email' => $data['email'],
                'telephone' => $data['telephone'],
            ]
        );

        return $customer->id;
    }

    private function itemFirstOrCreate($data)
    {
        $unit_type_id = $data['unit_type_id'];
        $unit_type = UnitType::firstOrNew(['id' => $unit_type_id]);
        if(!$unit_type->id) {
            $unit_type->description = $unit_type_id;
            $unit_type->save();
        }

        $item = Item::firstOrCreate(
            [
                'internal_id' => $data['internal_id']
            ],
            [
                'item_type_id' => '01',
                'unit_type_id' => $data['unit_type_id'],
                'description' => $data['description'],
                'unit_price' => $data['unit_price'],
            ]
        );

        return $item->id;
    }
}