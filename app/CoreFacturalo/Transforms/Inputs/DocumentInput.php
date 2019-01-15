<?php

namespace App\CoreFacturalo\Transforms\Inputs;

use App\CoreFacturalo\Helpers\Number\NumberLetter;
use App\CoreFacturalo\Transforms\FunctionInput;
use App\CoreFacturalo\Transforms\Inputs\Partials\ActionInput;
use App\CoreFacturalo\Transforms\Inputs\Partials\ChargeInput;
use App\CoreFacturalo\Transforms\Inputs\Partials\CustomerInput;
use App\CoreFacturalo\Transforms\Inputs\Partials\DetractionInput;
use App\CoreFacturalo\Transforms\Inputs\Partials\DiscountInput;
use App\CoreFacturalo\Transforms\Inputs\Partials\EstablishmentInput;
use App\CoreFacturalo\Transforms\Inputs\Partials\GuideInput;
use App\CoreFacturalo\Transforms\Inputs\Partials\ItemInput;
use App\CoreFacturalo\Transforms\Inputs\Partials\LegendInput;
use App\CoreFacturalo\Transforms\Inputs\Partials\OptionalInput;
use App\CoreFacturalo\Transforms\Inputs\Partials\PerceptionInput;
use App\CoreFacturalo\Transforms\Inputs\Partials\PrepaymentInput;
use App\CoreFacturalo\Transforms\Inputs\Partials\RelatedInput;
use App\CoreFacturalo\Transforms\Inputs\Partials\ItemAttributeInput;
use App\Models\Tenant\Document;
use App\Models\Tenant\Company;
use App\Models\Tenant\Series;
use Exception;
use Illuminate\Support\Str;

class DocumentInput
{
    public static function transform($inputs, $isWeb)
    {
        if($isWeb) {
            $document_type_id = $inputs['document_type_id'];
            $series = self::findSeries($inputs['series_id']);
            $number = $inputs['number'];
            $date_of_issue = $inputs['date_of_issue'];
            $time_of_issue = $inputs['time_of_issue'];
            $currency_type_id = $inputs['currency_type_id'];
            $purchase_order = array_key_exists('purchase_order', $inputs)?$inputs['purchase_order']:null;
            $exchange_rate_sale = $inputs['exchange_rate_sale'];

            $total_prepayment = $inputs['total_prepayment'];
            $total_discount = $inputs['total_discount'];
            $total_charge = $inputs['total_charge'];
            $total_exportation = $inputs['total_exportation'];
            $total_free = $inputs['total_free'];
            $total_taxed = $inputs['total_taxed'];
            $total_unaffected = $inputs['total_unaffected'];
            $total_exonerated = $inputs['total_exonerated'];
            $total_igv = $inputs['total_igv'];
            $total_base_isc = $inputs['total_base_isc'];
            $total_isc = $inputs['total_isc'];
            $total_base_other_taxes = $inputs['total_base_other_taxes'];
            $total_other_taxes = $inputs['total_other_taxes'];
            $total_taxes = $inputs['total_taxes'];
            $total_value = $inputs['total_value'];
            $total = $inputs['total'];
        } else {
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
        }

        self::validateDocumentTypeId($document_type_id);

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;
        $number = self::newNumber($soap_type_id, $document_type_id, $series, $number);
        $filename = self::filename($company, $document_type_id, $series, $number);

        self::validateUniqueDocument($soap_type_id, $document_type_id, $series, $number);

        $array_establishment = EstablishmentInput::transform($inputs, $isWeb);
        $array_customer = CustomerInput::transform($inputs, $isWeb);

        $items = ItemInput::transform($inputs, $isWeb);
        $charges = ChargeInput::transform($inputs, $isWeb);
        $discounts = DiscountInput::transform($inputs, $isWeb);
        $prepayments = PrepaymentInput::transform($inputs, $isWeb);
        $guides = GuideInput::transform($inputs, $isWeb);
        $related = RelatedInput::transform($inputs, $isWeb);
        $perception = PerceptionInput::transform($inputs, $isWeb);
        $detraction = DetractionInput::transform($inputs, $isWeb);
        $optional = OptionalInput::transform($inputs, $isWeb);
        $legends = LegendInput::transform($inputs, $isWeb);

        $attributes = ItemAttributeInput::transform($inputs, $isWeb);


        $legends[] = [
            'code' => 1000,
            'value' => NumberLetter::convertToLetter($total)
        ];

        return [
            'actions' => ActionInput::transform($inputs, $isWeb),
            'document' => [
                'type' => null,
                'group_id' => null,
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
                'customer_id' => $array_customer['customer_id'],
                'customer' => $array_customer['customer'],
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
                'attributes' => $attributes,
                'discounts' => $discounts,
                'prepayments' => $prepayments,
                'guides' => $guides,
                'related' => $related,
                'perception' => $perception,
                'detraction' => $detraction,
                'legends' => $legends,
                'optional' => $optional,
            ]
        ];
    }

    private static function newNumber($soap_type_id, $document_type_id, $series, $number)
    {
        if ($number === '#') {
            $document = Document::select('number')
                ->where('soap_type_id', $soap_type_id)
                ->where('document_type_id', $document_type_id)
                ->where('series', $series)
                ->orderBy('number', 'desc')
                ->first();
            return ($document)?(int)$document->number+1:1;
        }
        return $number;
    }

    private static function filename($company, $document_type_id, $series, $number)
    {
        return join('-', [$company->number, $document_type_id, $series, $number]);
    }

    private static function validateDocumentTypeId($document_type_id)
    {
        if(!in_array($document_type_id, ['01', '03', '07', '08'])) {
            throw new Exception("El código tipo de documento {$document_type_id} es incorrecto.");
        }
    }

    private static function validateUniqueDocument($soap_type_id, $document_type_id, $series, $number)
    {
        $document = Document::where('soap_type_id', $soap_type_id)
            ->where('document_type_id', $document_type_id)
            ->where('series', $series)
            ->where('number', $number)
            ->first();
        if($document) {
            throw new Exception("El documento: {$document_type_id} {$series}-{$number} ya se encuentra registrado.");
        }
    }

    private static function findSeries($series_id)
    {
        if(!$series_id) {
            throw new Exception("La serie es requerida");
        }

        return Series::find($series_id)->number;
    }
}