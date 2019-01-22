<?php

namespace App\CoreFacturalo\Transforms\Documents;

use App\CoreFacturalo\Transforms\Common\ActionTransform;
use App\CoreFacturalo\Transforms\Common\EstablishmentTransform;
use App\CoreFacturalo\Transforms\Common\PersonTransform;
use App\CoreFacturalo\Transforms\Common\LegendTransform;
use App\CoreFacturalo\Transforms\Documents\Partials\ChargeTransform;
use App\CoreFacturalo\Transforms\Documents\Partials\DetractionTransform;
use App\CoreFacturalo\Transforms\Documents\Partials\DiscountTransform;
use App\CoreFacturalo\Transforms\Documents\Partials\GuideTransform;
use App\CoreFacturalo\Transforms\Documents\Partials\InvoiceTransform;
use App\CoreFacturalo\Transforms\Documents\Partials\ItemTransform;
use App\CoreFacturalo\Transforms\Documents\Partials\NoteTransform;
use App\CoreFacturalo\Transforms\Documents\Partials\PerceptionTransform;
use App\CoreFacturalo\Transforms\Documents\Partials\PrepaymentTransform;
use App\CoreFacturalo\Transforms\Documents\Partials\RelatedTransform;
use App\CoreFacturalo\Transforms\TransformFunctions;

class DocumentTransform
{
    public static function transform($inputs)
    {
        $totals = $inputs['totales'];

        return [
            'series' => TransformFunctions::valueKeyInArray($inputs, 'serie_documento'),
            'number' => TransformFunctions::valueKeyInArray($inputs, 'numero_documento'),
            'date_of_issue' => TransformFunctions::valueKeyInArray($inputs, 'fecha_de_emision'),
            'time_of_issue' => TransformFunctions::valueKeyInArray($inputs, 'hora_de_emision'),
            'document_type_id' => TransformFunctions::valueKeyInArray($inputs, 'codigo_tipo_documento'),
            'currency_type_id' => TransformFunctions::valueKeyInArray($inputs, 'codigo_tipo_moneda'),
            'exchange_rate_sale' => TransformFunctions::valueKeyInArray($inputs, 'factor_tipo_de_cambio', 1),
            'purchase_order' => TransformFunctions::valueKeyInArray($inputs, 'numero_orden_de_compra'),
            'establishment' => EstablishmentTransform::transform($inputs['datos_del_emisor']),
            'customer' => PersonTransform::transform($inputs['datos_del_cliente_o_receptor']),
            'charges' => ChargeTransform::transform($inputs),
            'discounts' => DiscountTransform::transform($inputs),
            'total_prepayment' => TransformFunctions::valueKeyInArray($totals, 'total_anticipos'),
            'total_discount' => TransformFunctions::valueKeyInArray($totals, 'total_descuentos'),
            'total_charge' => TransformFunctions::valueKeyInArray($totals, 'total_cargos'),
            'total_exportation' => TransformFunctions::valueKeyInArray($totals, 'total_exportacion'),
            'total_free' => TransformFunctions::valueKeyInArray($totals, 'total_operaciones_gratuitas'),
            'total_taxed' => TransformFunctions::valueKeyInArray($totals, 'total_operaciones_gravadas'),
            'total_unaffected' => TransformFunctions::valueKeyInArray($totals, 'total_operaciones_inafectas'),
            'total_exonerated' => TransformFunctions::valueKeyInArray($totals, 'total_operaciones_exoneradas'),
            'total_igv' => TransformFunctions::valueKeyInArray($totals, 'total_igv'),
            'total_base_isc' => TransformFunctions::valueKeyInArray($totals, 'total_base_isc'),
            'total_isc' => TransformFunctions::valueKeyInArray($totals, 'total_isc'),
            'total_base_other_taxes' => TransformFunctions::valueKeyInArray($totals, 'total_base_otros_impuestos'),
            'total_other_taxes' => TransformFunctions::valueKeyInArray($totals, 'total_otros_impuestos'),
            'total_taxes' => TransformFunctions::valueKeyInArray($totals, 'total_impuestos'),
            'total_value' => TransformFunctions::valueKeyInArray($totals, 'total_valor'),
            'total' => TransformFunctions::valueKeyInArray($totals, 'total_venta'),
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
    }


}