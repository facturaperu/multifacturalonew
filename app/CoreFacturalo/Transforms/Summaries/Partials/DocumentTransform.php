<?php

namespace App\CoreFacturalo\Transforms\Summaries\Partials;

use App\CoreFacturalo\Transforms\Functions;

class DocumentTransform
{
    public static function transform($inputs)
    {
        if(key_exists('documentos', $inputs)) {
            $documents = [];
            foreach ($inputs['documentos'] as $row)
            {
                $documents[] = [
                    'external_id' => Functions::valueKeyInArray($row, 'external_id'),
                    'description' => Functions::valueKeyInArray($row, 'motivo_anulacion'),
                ];
            }
            return $documents;
        }
        return [];
    }
}