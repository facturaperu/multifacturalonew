<?php

namespace App\CoreFacturalo\Transforms\Api\Voided;

use App\CoreFacturalo\Transforms\Api\Voided\Partials\DocumentTransform;

class VoidedTransform
{
    public static function transform($inputs)
    {
        return [
            'date_of_reference' => $inputs['fecha_de_emision_de_documentos'],
            'documents' => DocumentTransform::transform($inputs),
        ];
    }
}