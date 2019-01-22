<?php

namespace App\CoreFacturalo\Transforms\Voided\Partials;

use App\CoreFacturalo\Transforms\TransformFunctions;

class DocumentTransform
{
    public static function transform($inputs)
    {
        if(key_exists('documentos', $inputs)) {
            $documents = [];
            foreach ($inputs['documentos'] as $row)
            {
                $documents[] = [
                    'external_id' => TransformFunctions::valueKeyInArray($row, 'external_id'),
                    'description' => TransformFunctions::valueKeyInArray($row, 'motivo_anulacion'),
                ];
            }
            return $documents;
        }
        return [];
    }
}