<?php

namespace App\CoreFacturalo\Transforms\Api\Documents;

use App\CoreFacturalo\Transforms\Api\Common\ActionTransform;
use App\CoreFacturalo\Transforms\Api\Common\EstablishmentTransform;
use App\CoreFacturalo\Transforms\Api\Common\PersonTransform;
use App\CoreFacturalo\Transforms\Api\Common\LegendTransform;
use App\CoreFacturalo\Transforms\Api\Documents\Partials\ChargeTransform;
use App\CoreFacturalo\Transforms\Api\Documents\Partials\DetractionTransform;
use App\CoreFacturalo\Transforms\Api\Documents\Partials\DiscountTransform;
use App\CoreFacturalo\Transforms\Api\Documents\Partials\GuideTransform;
use App\CoreFacturalo\Transforms\Api\Documents\Partials\InvoiceTransform;
use App\CoreFacturalo\Transforms\Api\Documents\Partials\ItemTransform;
use App\CoreFacturalo\Transforms\Api\Documents\Partials\NoteTransform;
use App\CoreFacturalo\Transforms\Api\Documents\Partials\PerceptionTransform;
use App\CoreFacturalo\Transforms\Api\Documents\Partials\PrepaymentTransform;
use App\CoreFacturalo\Transforms\Api\Documents\Partials\RelatedTransform;
use App\CoreFacturalo\Transforms\Api\FunctionsTransform;

class DocumentTransform
{
    public static function transform($inputs)
    {
        $totals = $inputs['totales'];

        $transform_inputs = [
            'series' => FunctionsTransform::valueKeyInArray($inputs, 'serie_documento'),
            'number' => FunctionsTransform::valueKeyInArray($inputs, 'numero_documento'),
            'date_of_issue' => FunctionsTransform::valueKeyInArray($inputs, 'fecha_de_emision'),
            'time_of_issue' => FunctionsTransform::valueKeyInArray($inputs, 'hora_de_emision'),
            'document_type_id' => FunctionsTransform::valueKeyInArray($inputs, 'codigo_tipo_documento'),
            'currency_type_id' => FunctionsTransform::valueKeyInArray($inputs, 'codigo_tipo_moneda'),
            'exchange_rate_sale' => FunctionsTransform::valueKeyInArray($inputs, 'factor_tipo_de_cambio', 1),
            'purchase_order' => FunctionsTransform::valueKeyInArray($inputs, 'numero_orden_de_compra'),
            'establishment_id' => EstablishmentTransform::transform($inputs['datos_del_emisor']),
            'customer_id' => PersonTransform::transform($inputs['datos_del_cliente_o_receptor'], 'customer'),
            'charges' => ChargeTransform::transform($inputs),
            'discounts' => DiscountTransform::transform($inputs),
            'total_prepayment' => FunctionsTransform::valueKeyInArray($totals, 'total_anticipos'),
            'total_discount' => FunctionsTransform::valueKeyInArray($totals, 'total_descuentos'),
            'total_charge' => FunctionsTransform::valueKeyInArray($totals, 'total_cargos'),
            'total_exportation' => FunctionsTransform::valueKeyInArray($totals, 'total_exportacion'),
            'total_free' => FunctionsTransform::valueKeyInArray($totals, 'total_operaciones_gratuitas'),
            'total_taxed' => FunctionsTransform::valueKeyInArray($totals, 'total_operaciones_gravadas'),
            'total_unaffected' => FunctionsTransform::valueKeyInArray($totals, 'total_operaciones_inafectas'),
            'total_exonerated' => FunctionsTransform::valueKeyInArray($totals, 'total_operaciones_exoneradas'),
            'total_igv' => FunctionsTransform::valueKeyInArray($totals, 'total_igv'),
            'total_base_isc' => FunctionsTransform::valueKeyInArray($totals, 'total_base_isc'),
            'total_isc' => FunctionsTransform::valueKeyInArray($totals, 'total_isc'),
            'total_base_other_taxes' => FunctionsTransform::valueKeyInArray($totals, 'total_base_otros_impuestos'),
            'total_other_taxes' => FunctionsTransform::valueKeyInArray($totals, 'total_otros_impuestos'),
            'total_taxes' => FunctionsTransform::valueKeyInArray($totals, 'total_impuestos'),
            'total_value' => FunctionsTransform::valueKeyInArray($totals, 'total_valor'),
            'total' => FunctionsTransform::valueKeyInArray($totals, 'total_venta'),
            'items' => ItemTransform::transform($inputs),
            'detraction' => DetractionTransform::transform($inputs),
            'perception' => PerceptionTransform::transform($inputs),
            'prepayments' => PrepaymentTransform::transform($inputs),
            'guides' => GuideTransform::transform($inputs),
            'related' => RelatedTransform::transform($inputs),
            'legends' => LegendTransform::transform($inputs),
            'invoice' => InvoiceTransform::transform($inputs),
            'note' => NoteTransform::transform($inputs),
            'actions' => ActionTransform::transform($inputs),
        ];

        FunctionsTransform::validateSeries($transform_inputs);


        return $transform_inputs;
    }
}