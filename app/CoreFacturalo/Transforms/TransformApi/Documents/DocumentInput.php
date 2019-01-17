<?php

namespace App\CoreFacturalo\Transforms\TransformApi\Documents;

use App\CoreFacturalo\Helpers\Number\NumberLetter;
use App\CoreFacturalo\Transforms\TransApi\Common\ActionInput;
use App\CoreFacturalo\Transforms\TransformApi\Common\EstablishmentInput;
use App\CoreFacturalo\Transforms\TransformApi\Common\PersonInput;
use App\CoreFacturalo\Transforms\TransformApi\Documents\Partials\ChargeInput;
use App\CoreFacturalo\Transforms\TransformApi\Documents\Partials\DetractionInput;
use App\CoreFacturalo\Transforms\TransformApi\Documents\Partials\DiscountInput;
use App\CoreFacturalo\Transforms\TransformApi\Documents\Partials\GuideInput;
use App\CoreFacturalo\Transforms\TransformApi\Documents\Partials\ItemInput;
use App\CoreFacturalo\Transforms\TransformApi\Documents\Partials\LegendInput;
use App\CoreFacturalo\Transforms\TransformApi\Documents\Partials\OptionalInput;
use App\CoreFacturalo\Transforms\TransformApi\Documents\Partials\PerceptionInput;
use App\CoreFacturalo\Transforms\TransformApi\Documents\Partials\PrepaymentInput;
use App\CoreFacturalo\Transforms\TransformApi\Documents\Partials\RelatedInput;

class DocumentInput
{
    public static function transform($inputs)
    {
        $new_inputs['document_type_id'] = $inputs['codigo_tipo_documento'];
        $new_inputs['series'] = $inputs['serie_documento'];
        $new_inputs['number'] = $inputs['numero_documento'];
        $new_inputs['date_of_issue'] = $inputs['fecha_de_emision'];
        $new_inputs['time_of_issue'] = $inputs['hora_de_emision'];
        $new_inputs['date_of_due'] = array_key_exists('fecha_de_vencimiento', $inputs)?$inputs['fecha_de_vencimiento']:null;
        $new_inputs['operation_type_id'] = array_key_exists('codigo_tipo_operacion', $inputs)?$inputs['codigo_tipo_operacion']:null;
        $new_inputs['currency_type_id'] = $inputs['codigo_tipo_moneda'];
        $new_inputs['purchase_order'] = array_key_exists('numero_orden_de_compra', $inputs)?$inputs['numero_orden_de_compra']:null;
        $new_inputs['exchange_rate_sale'] = array_key_exists('tipo_de_cambio_factor', $inputs)?$inputs['tipo_de_cambio_factor']:0;

        $totals = $inputs['totales'];
        $new_inputs['total_prepayment'] = array_key_exists('total_anticipos', $totals)?$totals['total_anticipos']:0;
        $new_inputs['total_discount'] = array_key_exists('total_descuentos', $totals)?$totals['total_descuentos']:0;
        $new_inputs['total_charge'] = array_key_exists('total_cargos', $totals)?$totals['total_cargos']:0;
        $new_inputs['total_exportation'] = array_key_exists('total_exportacion', $totals)?$totals['total_exportacion']:0;
        $new_inputs['total_free'] = array_key_exists('total_operaciones_gratuitas', $totals)?$totals['total_operaciones_gratuitas']:0;
        $new_inputs['total_taxed'] = array_key_exists('total_operaciones_gravadas', $totals)?$totals['total_operaciones_gravadas']:0;
        $new_inputs['total_unaffected'] = array_key_exists('total_operaciones_inafectas', $totals)?$totals['total_operaciones_inafectas']:0;
        $new_inputs['total_exonerated'] = array_key_exists('total_operaciones_exoneradas', $totals)?$totals['total_operaciones_exoneradas']:0;
        $new_inputs['total_igv'] = array_key_exists('total_igv', $totals)?$totals['total_igv']:0;
        $new_inputs['total_base_isc'] = array_key_exists('total_base_isc', $totals)?$totals['total_base_isc']:0;
        $new_inputs['total_isc'] = array_key_exists('total_isc', $totals)?$totals['total_isc']:0;
        $new_inputs['total_base_other_taxes'] = array_key_exists('total_base_otros_impuestos', $totals)?$totals['total_base_otros_impuestos']:0;
        $new_inputs['total_other_taxes'] = array_key_exists('total_otros_impuestos', $totals)?$totals['total_otros_impuestos']:0;
        $new_inputs['total_taxes'] = array_key_exists('total_impuestos', $totals)?$totals['total_impuestos']:0;
        $new_inputs['total_value'] = array_key_exists('total_valor', $totals)?$totals['total_valor']:0;
        $new_inputs['total'] = $totals['total_venta'];

        $new_inputs['establishment_id'] = EstablishmentInput::transform($inputs['datos_del_emisor']);
        $new_inputs['customer_id'] = PersonInput::transform($inputs['datos_del_cliente_o_receptor']);

        $new_inputs['items'] = ItemInput::transform($inputs);
        $new_inputs['charges'] = ChargeInput::transform($inputs);
        $new_inputs['discounts'] = DiscountInput::transform($inputs);
        $new_inputs['prepayments'] = PrepaymentInput::transform($inputs);
        $new_inputs['guides'] = GuideInput::transform($inputs);
        $new_inputs['related'] = RelatedInput::transform($inputs);
        $new_inputs['perception'] = PerceptionInput::transform($inputs);
        $new_inputs['detraction'] = DetractionInput::transform($inputs);
        $new_inputs['optional'] = OptionalInput::transform($inputs);
        $new_inputs['legends'] = LegendInput::transform($inputs);
        $new_inputs['actions'] = ActionInput::transform($inputs);

        return $new_inputs;
    }
}