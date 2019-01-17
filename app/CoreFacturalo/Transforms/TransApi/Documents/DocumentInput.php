<?php

namespace App\CoreFacturalo\Transforms\TransApi\Documents;

use App\CoreFacturalo\Helpers\Number\NumberLetter;
use App\CoreFacturalo\Transforms\Functions;
use App\CoreFacturalo\Transforms\TransApi\Common\ActionInput;
use App\CoreFacturalo\Transforms\TransApi\Common\EstablishmentInput;
use App\CoreFacturalo\Transforms\TransApi\Common\PersonInput;
use App\CoreFacturalo\Transforms\TransApi\Documents\Partials\ChargeInput;
use App\CoreFacturalo\Transforms\TransApi\Documents\Partials\DetractionInput;
use App\CoreFacturalo\Transforms\TransApi\Documents\Partials\DiscountInput;
use App\CoreFacturalo\Transforms\TransApi\Documents\Partials\GuideInput;
use App\CoreFacturalo\Transforms\TransApi\Documents\Partials\ItemInput;
use App\CoreFacturalo\Transforms\TransApi\Documents\Partials\LegendInput;
use App\CoreFacturalo\Transforms\TransApi\Documents\Partials\OptionalInput;
use App\CoreFacturalo\Transforms\TransApi\Documents\Partials\PerceptionInput;
use App\CoreFacturalo\Transforms\TransApi\Documents\Partials\PrepaymentInput;
use App\CoreFacturalo\Transforms\TransApi\Documents\Partials\RelatedInput;
use App\Models\Tenant\Document;
use App\Models\Tenant\Company;
use App\Models\Tenant\Series;
use Exception;
use Illuminate\Support\Str;

class DocumentInput
{
    public static function transform($inputs)
    {
        $document_type_id = $inputs['codigo_tipo_documento'];
        $series = $inputs['serie_documento'];
        $number = $inputs['numero_documento'];
        $date_of_issue = $inputs['fecha_de_emision'];
        $time_of_issue = $inputs['hora_de_emision'];
        $currency_type_id = $inputs['codigo_tipo_moneda'];
        $purchase_order = array_key_exists('numero_orden_de_compra', $inputs)?$inputs['numero_orden_de_compra']:null;
        $exchange_rate_sale = 0;

        $totals = $inputs['totales'];
        $total_prepayment = array_key_exists('total_anticipos', $totals)?$totals['total_anticipos']:0;
        $total_discount = array_key_exists('total_descuentos', $totals)?$totals['total_descuentos']:0;
        $total_charge = array_key_exists('total_cargos', $totals)?$totals['total_cargos']:0;
        $total_exportation = array_key_exists('total_exportacion', $totals)?$totals['total_exportacion']:0;
        $total_free = array_key_exists('total_operaciones_gratuitas', $totals)?$totals['total_operaciones_gratuitas']:0;
        $total_taxed = array_key_exists('total_operaciones_gravadas', $totals)?$totals['total_operaciones_gravadas']:0;
        $total_unaffected = array_key_exists('total_operaciones_inafectas', $totals)?$totals['total_operaciones_inafectas']:0;
        $total_exonerated = array_key_exists('total_operaciones_exoneradas', $totals)?$totals['total_operaciones_exoneradas']:0;
        $total_igv = array_key_exists('total_igv', $totals)?$totals['total_igv']:0;
        $total_base_isc = array_key_exists('total_base_isc', $totals)?$totals['total_base_isc']:0;
        $total_isc = array_key_exists('total_isc', $totals)?$totals['total_isc']:0;
        $total_base_other_taxes = array_key_exists('total_base_otros_impuestos', $totals)?$totals['total_base_otros_impuestos']:0;
        $total_other_taxes = array_key_exists('total_otros_impuestos', $totals)?$totals['total_otros_impuestos']:0;
        $total_taxes = array_key_exists('total_impuestos', $totals)?$totals['total_impuestos']:0;
        $total_value = array_key_exists('total_valor', $totals)?$totals['total_valor']:0;
        $total = $totals['total_venta'];

        Functions::validateDocumentTypeId($document_type_id, ['01', '03', '07', '08']);

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;
        $number = Functions::newNumber($soap_type_id, $document_type_id, $series, $number, Document::class);
        $filename = Functions::filename($company, $document_type_id, $series, $number);

        Functions::validateUniqueDocument($soap_type_id, $document_type_id, $series, $number, Document::class);

        $array_establishment = EstablishmentInput::transform($inputs);
        $array_customer = PersonInput::transform($inputs['datos_del_cliente_o_receptor']);

        Functions::validateSeries($series, $document_type_id, $array_establishment['establishment_id']);

        $items = ItemInput::transform($inputs);
        $charges = ChargeInput::transform($inputs);
        $discounts = DiscountInput::transform($inputs);
        $prepayments = PrepaymentInput::transform($inputs);
        $guides = GuideInput::transform($inputs);
        $related = RelatedInput::transform($inputs);
        $perception = PerceptionInput::transform($inputs);
        $detraction = DetractionInput::transform($inputs);
        $optional = OptionalInput::transform($inputs);
        $legends = LegendInput::transform($inputs);

        $legends[] = [
            'code' => 1000,
            'value' => NumberLetter::convertToLetter($total)
        ];

        $invoice = null;
        $note = null;
        $type = 'invoice';
        $group_id = null;

        if(in_array($document_type_id, ['01', '03'])) {
            $group_id = ($document_type_id === '01')?'01':'02';
            $invoice = [
                'date_of_due' => $inputs['fecha_de_vencimiento'],
                'operation_type_id' => $inputs['codigo_tipo_operacion']
            ];
        } else {
            $note_credit_or_debit_type_id = $inputs['codigo_tipo_nota'];
            $note_description = $inputs['motivo_o_sustento_de_nota'];

            $aux_aff_document = $inputs['documento_afectado'];
            $aff_document_type_id = $aux_aff_document['codigo_tipo_documento'];
            $aff_document_series = $aux_aff_document['serie_documento'];
            $aff_document_number = $aux_aff_document['numero_documento'];

            $aff_document = Document::where('document_type_id', $aff_document_type_id)
                                    ->where('series', $aff_document_series)
                                    ->where('number', $aff_document_number)
                                    ->first();

            if(!$aff_document) {
                throw new Exception("El documento afectado no se encuentra registrado.");
            }

            if($aff_document->state_type_id !== '05') {
                throw new Exception("El documento afectado no tiene el estado aceptado.");
            }

            $group_id = $aff_document->group_id;
            if ($document_type_id === '07') {
                $note_type = 'credit';
                $note_credit_type_id = $note_credit_or_debit_type_id;
                $note_debit_type_id = null;
                $type = 'credit';
            } else {
                $note_type = 'debit';
                $note_credit_type_id = null;
                $note_debit_type_id = $note_credit_or_debit_type_id;
                $type = 'debit';
            }

            $note = [
                'note_type' => $note_type,
                'note_credit_type_id' => $note_credit_type_id,
                'note_debit_type_id' => $note_debit_type_id,
                'note_description' => $note_description,
                'affected_document_id' => $aff_document->id,
            ];
        }

        return [
            'type' => $type,
            'group_id' => $group_id,
            'user_id' => auth()->id(),
            'external_id' => Str::uuid(),
            'establishment_id' => $array_establishment['establishment_id'],
            'establishment' => $array_establishment['establishment'],
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
            'ubl_version' => '2.1',
            'filename' => $filename,
            'document_type_id' => $document_type_id,
            'series' => $series,
            'number' => $number,
            'date_of_issue' => $date_of_issue,
            'time_of_issue' => $time_of_issue,
            'customer_id' => $array_customer['person_id'],
            'customer' => $array_customer['person'],
            'currency_type_id' => $currency_type_id,
            'purchase_order' => $purchase_order,
            'exchange_rate_sale' => $exchange_rate_sale,
            'total_prepayment' => $total_prepayment,
            'total_discount' => $total_discount,
            'total_charge' => $total_charge,
            'total_exportation' => $total_exportation,
            'total_free' => $total_free,
            'total_taxed' => $total_taxed,
            'total_unaffected' => $total_unaffected,
            'total_exonerated' => $total_exonerated,
            'total_igv' => $total_igv,
            'total_base_isc' => $total_base_isc,
            'total_isc' => $total_isc,
            'total_base_other_taxes' => $total_base_other_taxes,
            'total_other_taxes' => $total_other_taxes,
            'total_taxes' => $total_taxes,
            'total_value' => $total_value,
            'total' => $total,
            'items' => $items,
            'charges' => $charges,
            'discounts' => $discounts,
            'prepayments' => $prepayments,
            'guides' => $guides,
            'related' => $related,
            'perception' => $perception,
            'detraction' => $detraction,
            'legends' => $legends,
            'optional' => $optional,
            'invoice' => $invoice,
            'note' => $note,
            'actions' => ActionInput::transform($inputs)
        ];
    }
}