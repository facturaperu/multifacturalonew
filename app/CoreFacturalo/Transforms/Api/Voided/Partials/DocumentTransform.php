<?php

namespace App\CoreFacturalo\Transforms\Api\Voided\Partials;

use App\CoreFacturalo\Transforms\Api\FunctionsTransform;

class DocumentTransform
{
    public static function transform($inputs)
    {
        if(key_exists('documentos', $inputs)) {
            $documents = [];
            foreach ($inputs['documentos'] as $row)
            {
                $documents[] = [
                    'external_id' => FunctionsTransform::valueKeyInArray($row, 'external_id'),
                    'description' => FunctionsTransform::valueKeyInArray($row, 'motivo_anulacion'),
                ];
            }
            return $documents;
        }
        return [];
    }
}