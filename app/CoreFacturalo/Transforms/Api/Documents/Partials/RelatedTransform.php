<?php

namespace App\CoreFacturalo\Transforms\Api\Documents\Partials;

class RelatedTransform
{
    public static function transform($inputs)
    {
        if(key_exists('relacionados', $inputs)) {
            $related = [];
            foreach ($inputs['relacionados'] as $row)
            {
                $related[] = [
                    'number' => $row['numero'],
                    'document_type_id' => $row['codigo_tipo_documento'],
                    'amount' => $row['monto']
                ];
            }

            return $related;
        }
        return null;
    }
}