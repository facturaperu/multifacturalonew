<?php

namespace App\Http\Middleware;

use App\Core\Helpers\NumberHelper;
use App\Models\Tenant\Catalogs\Code;
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

                $items[] = [
                    'internal_id' => array_key_exists('codigo_interno', $row)?$row['codigo_interno']:null,
                    'item_description' => $row['descripcion'],
                    'item_code' => array_key_exists('codigo_producto_de_sunat', $row)?$row['codigo_producto_sunat']:null,
                    'item_code_gs1' => array_key_exists('codigo_producto_gsl', $row)?$row['codigo_producto_gsl']:null,
                    'unit_type_id' => $row['unidad_de_medida'],
                    'quantity' => $row['cantidad'],
                    'unit_value' => $row['valor_unitario'],

                    'affectation_igv_type_id' => $row['codigo_tipo_afectacion_igv'],
                    'total_base_igv' => $row['total_base_igv'],
                    'percentage_igv' => $row['porcentaje_de_igv'],
                    'total_igv' => $row['total_igv'],

                    'system_isc_type_id' => array_key_exists('codigo_tipo_sistema_isc', $row)?$row['codigo_tipo_sistema_isc']:null,
                    'total_base_isc' => array_key_exists('total_base_isc', $row)?$row['total_base_isc']:0,
                    'percentage_isc' => array_key_exists('porcentaje_de_isc', $row)?$row['porcentaje_de_isc']:0,
                    'total_isc' => array_key_exists('total_isc', $row)?$row['total_isc']:0,

                    'total_base_other_taxes' => array_key_exists('total_base_otros_impuestos', $row)?$row['total_base_otros_impuestos']:0,
                    'percentage_other_taxes' => array_key_exists('percentage_other_taxes', $row)?$row['percentage_other_taxes']:0,
                    'total_other_taxes' => array_key_exists('total_otros_impuestos', $row)?$row['total_otros_impuestos']:0,

                    'total_taxes' => $row['total_impuestos'],

                    'price_type_id' => $row['codigo_tipo_precio'],
                    'unit_price' => $row['precio_unitario'],

                    'total_value' => $row['valor_de_venta_por_item'],
                    'total' => $row['total_por_item'],

                    'attributes' => $attributes,
                    'charges' => $charges,
                    'discounts' => $discounts
                ];
            }

            $prepayments = null;
//        if(array_key_exists('informacion_adicional_anticipos', $inputs)) {
//                $serie_number = explode('-',$inputs['informacion_adicional_anticipos']['informacion_prepagado_o_anticipado']['serie_y_numero_de_documento_que_se_realizo_el_anticipo']);
//                $prepayments[] = [
//                    'series' => $serie_number[0],
//                    'number' => $serie_number[1],
//                    'document_type_code' => $inputs['informacion_adicional_anticipos']['informacion_prepagado_o_anticipado']['tipo_de_comprobante_que_se_realizo_el_anticipo'],
//                    'currency_type_code' => $inputs['informacion_adicional_anticipos']['informacion_prepagado_o_anticipado']['tipo_de_documento_del_emisor_del_anticipo'],
//                    'total' => array_key_exists('total_anticipos', $inputs['informacion_adicional_anticipos'])?$inputs['informacion_adicional_anticipos']['total_anticipos']:0,
//                ];
//        }
//
            $additional_documents = null;
//        if(array_key_exists('DocumentosAdicionalesRelacionados', $inputs)) {
//            foreach ($inputs['DocumentosAdicionalesRelacionados'] as $row)
//            {
//                $additional_documents[] = [
//                    'number' => $row['NumeroDocumento'],
//                    'document_type_code' => $row['CodigoTipoDocumento'],
//                ];
//            }
//        }

            $perception = null;
            if(array_key_exists('percepcion', $inputs)) {
                $perception = [
                    'code' => $inputs['percepcion']['codigo'],
                    'percentage' => $inputs['percepcion']['porcentaje'],
                    'amount' => $inputs['percepcion']['monto'],
                    'base' => $inputs['percepcion']['base'],
                ];
            }
//
            $detraction = null;
//        if(array_key_exists('Detraccion', $inputs)) {
//            $detraction = [
//                'account' => $inputs['Detraccion']['CuentaBancoNacion'],
//                'code' => $inputs['Detraccion']['CodigoBienServicio'],
//                'percentage' => $inputs['Detraccion']['PorcentajeDetraccion'],
//                'total' => $inputs['Detraccion']['TotalDetraccion'],
//            ];
//        }

            $optional = [];
            if(array_key_exists('extras', $inputs)) {
                $optional = [
                    'observations' => array_key_exists('observaciones', $inputs['extras'])?$inputs['extras']['observaciones']:null,
                    'method_payment' => array_key_exists('forma_de_pago', $inputs['extras'])?$inputs['extras']['forma_de_pago']:null,
                    'salesman' => array_key_exists('vendedor', $inputs['extras'])?$inputs['extras']['vendedor']:null,
                    'box_number' => array_key_exists('caja', $inputs['extras'])?$inputs['extras']['caja']:null ,
                    'format_pdf' => array_key_exists('formato_pdf', $inputs['extras'])?$inputs['extras']['formato_pdf']:'a4'
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
            $total = $inputs['totales']['total_de_la_venta'];

            // Date Variables
            $date_of_issue = $inputs['fecha_de_emision'];
            $time_of_issue = $inputs['hora_de_emision'];
            $date_of_due = array_key_exists('fecha_de_vencimiento', $inputs)?$inputs['fecha_de_vencimiento']:null;

            // Document Variables
            $document_type_id = $inputs['tipo_de_documento'];
            $ubl_version = $inputs['version_del_ubl'];
            $currency_type_id = $inputs['tipo_de_moneda'];
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

            $purchase_order = array_key_exists('orden_compra', $inputs)?$inputs['orden_compra']:null;
            $establishment = [
                'code' => $inputs['datos_del_emisor']['codigo_del_domicilio_fiscal']
            ];
            $customer = [
                'identity_document_type_id' => $inputs['datos_del_cliente_o_receptor']['tipo_de_documento_identidad'],
                'number' => $inputs['datos_del_cliente_o_receptor']['numero_de_documento'],
                'name' => $inputs['datos_del_cliente_o_receptor']['apellidos_y_nombres_o_razon_social'],
                'trade_name' => array_key_exists('nombre_comercial', $inputs['datos_del_cliente_o_receptor'])?$inputs['datos_del_cliente_o_receptor']['nombre_comercial']:null,
                'address' => array_key_exists('address', $inputs['datos_del_cliente_o_receptor'])?$inputs['datos_del_cliente_o_receptor']['address']:null,
                'district_id' => array_key_exists('ubigeo', $inputs['datos_del_cliente_o_receptor'])?$inputs['datos_del_cliente_o_receptor']['ubigeo']:null,
                'country_id' => array_key_exists('codigo_pais', $inputs['datos_del_cliente_o_receptor'])?$inputs['datos_del_cliente_o_receptor']['codigo_pais']:null,
                'email' => array_key_exists('email', $inputs['datos_del_cliente_o_receptor'])?$inputs['datos_del_cliente_o_receptor']['email']:null,
                'telephone' => array_key_exists('telephone', $inputs['datos_del_cliente_o_receptor'])?$inputs['datos_del_cliente_o_receptor']['telephone']:null,
            ];

            $document_base = [];
            $group_id = null;
            // Invoice Variables
            $operation_type_id = array_key_exists('tipo_de_operacion', $inputs)?$inputs['tipo_de_operacion']:null;
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
                    'additional_documents' => $additional_documents,
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
                'number' => $data['number']
            ],
            [
                'identity_document_type_id' => $data['identity_document_type_id'],
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
        $unit_type_code = strtoupper($data['unidad_de_medida']);

        $unit_type = Code::firstOrNew([
            'catalog_id' => '03',
            'code' => $unit_type_code,
        ]);

        if(!$unit_type->id) {
            $unit_type->id = '03'.str_pad($unit_type_code, 6, '0');
            $unit_type->catalog_id = '03';
            $unit_type->code = $unit_type_code;
            $unit_type->description = $unit_type_code;
            $unit_type->active = true;
            $unit_type->save();
        }

        $item = Item::firstOrCreate(
            [
                'internal_id' => $data['codigo_interno_del_producto']
            ],
            [
                'item_type_id' => '01',
                'unit_type_id' => $unit_type->id,
                'description' => $data['descripcion_detallada'],
                'unit_price' => $data['precio_de_venta_unitario_valor_referencial'],
            ]
        );

        return $item->id;
    }
}