<?php

namespace App\CoreFacturalo\Transforms\Api\Retentions;

use App\CoreFacturalo\Transforms\Api\Common\ActionTransform;
use App\CoreFacturalo\Transforms\Api\Common\EstablishmentTransform;
use App\CoreFacturalo\Transforms\Api\Common\PersonTransform;
use App\CoreFacturalo\Transforms\Api\Common\LegendTransform;
use App\CoreFacturalo\Transforms\Api\FunctionsTransform;
use App\CoreFacturalo\Transforms\Api\Retentions\Partials\DocumentTransform;

class RetentionTransform
{
    public static function transform($inputs)
    {
        $totals = $inputs['totales'];
        return [
            'series' => FunctionsTransform::valueKeyInArray($inputs, 'serie_documento'),
            'number' => FunctionsTransform::valueKeyInArray($inputs, 'numero_documento'),
            'date_of_issue' => FunctionsTransform::valueKeyInArray($inputs, 'fecha_de_emision'),
            'time_of_issue' => FunctionsTransform::valueKeyInArray($inputs, 'hora_de_emision'),
            'document_type_id' => FunctionsTransform::valueKeyInArray($inputs, 'codigo_tipo_documento'),
            'establishment_id' => EstablishmentTransform::transform($inputs['datos_del_emisor']),
            'supplier_id' => PersonTransform::transform($inputs['datos_del_proveedor'], 'supplier'),
            'retention_type_id' => FunctionsTransform::valueKeyInArray($inputs, 'codigo_tipo_retencion'),
            'observations' => FunctionsTransform::valueKeyInArray($inputs, 'observaciones'),
            'total_retention' => FunctionsTransform::valueKeyInArray($totals, 'total_retenido'),
            'total' => FunctionsTransform::valueKeyInArray($totals, 'total_pagado'),
            'documents' => DocumentTransform::transform($inputs),
            'legends' => LegendTransform::transform($inputs),
            'actions' => ActionTransform::transform($inputs),
        ];
    }
}