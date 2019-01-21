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
use App\CoreFacturalo\Transforms\Functions;

class DocumentTransform
{
    public static function transform($inputs)
    {
        $totals = $inputs['totales'];

        return [
            'series' => Functions::valueKeyInArray($inputs, 'serie_documento'),
            'number' => Functions::valueKeyInArray($inputs, 'numero_documento'),
            'date_of_issue' => Functions::valueKeyInArray($inputs, 'fecha_de_emision'),
            'time_of_issue' => Functions::valueKeyInArray($inputs, 'hora_de_emision'),
            'document_type_id' => Functions::valueKeyInArray($inputs, 'codigo_tipo_documento'),
            'currency_type_id' => Functions::valueKeyInArray($inputs, 'codigo_tipo_moneda'),
            'exchange_rate_sale' => Functions::valueKeyInArray($inputs, 'factor_tipo_de_cambio'),
            'purchase_order' => Functions::valueKeyInArray($inputs, 'numero_orden_de_compra'),
            'establishment' => EstablishmentTransform::transform($inputs['datos_del_emisor']),
            'customer' => PersonTransform::transform($inputs['datos_del_cliente_o_receptor']),
            'charges' => ChargeTransform::transform($inputs),
            'discounts' => DiscountTransform::transform($inputs),
            'total_prepayment' => Functions::valueKeyInArray($totals, 'total_anticipos'),
            'total_discount' => Functions::valueKeyInArray($totals, 'total_descuentos'),
            'total_charge' => Functions::valueKeyInArray($totals, 'total_cargos'),
            'total_exportation' => Functions::valueKeyInArray($totals, 'total_exportacion'),
            'total_free' => Functions::valueKeyInArray($totals, 'total_operaciones_gratuitas'),
            'total_taxed' => Functions::valueKeyInArray($totals, 'total_operaciones_gravadas'),
            'total_unaffected' => Functions::valueKeyInArray($totals, 'total_operaciones_inafectas'),
            'total_exonerated' => Functions::valueKeyInArray($totals, 'total_operaciones_exoneradas'),
            'total_igv' => Functions::valueKeyInArray($totals, 'total_igv'),
            'total_base_isc' => Functions::valueKeyInArray($totals, 'total_base_isc'),
            'total_isc' => Functions::valueKeyInArray($totals, 'total_isc'),
            'total_base_other_taxes' => Functions::valueKeyInArray($totals, 'total_base_otros_impuestos'),
            'total_other_taxes' => Functions::valueKeyInArray($totals, 'total_otros_impuestos'),
            'total_taxes' => Functions::valueKeyInArray($totals, 'total_impuestos'),
            'total_value' => Functions::valueKeyInArray($totals, 'total_valor'),
            'total' => Functions::valueKeyInArray($totals, 'total_venta'),
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