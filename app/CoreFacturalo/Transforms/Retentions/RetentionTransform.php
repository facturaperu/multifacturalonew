<?php

namespace App\CoreFacturalo\Transforms\Retentions;

use App\CoreFacturalo\Transforms\Common\ActionTransform;
use App\CoreFacturalo\Transforms\Common\EstablishmentTransform;
use App\CoreFacturalo\Transforms\Common\PersonTransform;
use App\CoreFacturalo\Transforms\Common\LegendTransform;
use App\CoreFacturalo\Transforms\Functions;
use App\CoreFacturalo\Transforms\Retentions\Partials\DocumentTransform;

class RetentionTransform
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
            'establishment_id' => EstablishmentTransform::transform($inputs['datos_del_emisor']),
            'supplier_id' => PersonTransform::transform($inputs['datos_del_proveedor']),
            'retention_type_id' => Functions::valueKeyInArray($inputs, 'codigo_tipo_retencion'),
            'observations' => Functions::valueKeyInArray($inputs, 'observaciones'),
            'total_retention' => Functions::valueKeyInArray($totals, 'total_retenido'),
            'total' => Functions::valueKeyInArray($totals, 'total_pagado'),
            'documents' => DocumentTransform::transform($inputs),
            'legends' => LegendTransform::transform($inputs),
            'actions' => ActionTransform::transform($inputs),
        ];
    }
}