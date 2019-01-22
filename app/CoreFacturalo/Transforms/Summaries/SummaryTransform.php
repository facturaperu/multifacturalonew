<?php

namespace App\CoreFacturalo\Transforms\Summaries;

use App\CoreFacturalo\Transforms\Common\ActionTransform;
use App\CoreFacturalo\Transforms\Summaries\Partials\DocumentTransform;

class SummaryTransform
{
    public static function transform($inputs)
    {
        return [
            'date_of_reference' => $inputs['fecha_de_emision_de_documentos'],
            'summary_status_type_id' => $inputs['codigo_tipo_proceso'],
            'documents' => DocumentTransform::transform($inputs),
//            'actions' => ActionTransform::transform($inputs),
        ];
    }
}