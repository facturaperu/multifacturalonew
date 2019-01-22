<?php

namespace App\CoreFacturalo\Transforms\Retentions;

use App\CoreFacturalo\Transforms\Common\ActionTransform;
use App\CoreFacturalo\Transforms\Common\EstablishmentTransform;
use App\CoreFacturalo\Transforms\Common\PersonTransform;
use App\CoreFacturalo\Transforms\Common\LegendTransform;
use App\CoreFacturalo\Transforms\TransformFunctions;
use App\CoreFacturalo\Transforms\Retentions\Partials\DocumentTransform;

class RetentionTransform
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
            'establishment' => EstablishmentTransform::transform($inputs['datos_del_emisor']),
            'supplier' => PersonTransform::transform($inputs['datos_del_proveedor']),
            'retention_type_id' => TransformFunctions::valueKeyInArray($inputs, 'codigo_tipo_retencion'),
            'observations' => TransformFunctions::valueKeyInArray($inputs, 'observaciones'),
            'total_retention' => TransformFunctions::valueKeyInArray($totals, 'total_retenido'),
            'total' => TransformFunctions::valueKeyInArray($totals, 'total_pagado'),
            'documents' => DocumentTransform::transform($inputs),
            'legends' => LegendTransform::transform($inputs),
            'actions' => ActionTransform::transform($inputs),
        ];
    }
}