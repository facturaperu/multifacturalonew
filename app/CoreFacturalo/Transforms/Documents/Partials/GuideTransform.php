<?php

namespace App\CoreFacturalo\Transforms\Documents\Partials;

class GuideTransform
{
    public static function transform($inputs)
    {
        if(key_exists('guias', $inputs)) {
            $guides = [];
            foreach ($inputs['guias'] as $row)
            {
                $guides[] = [
                    'number' => $row['numero'],
                    'document_type_id' => $row['codigo_tipo_documento'],
                ];
            }

            return $guides;
        }
        return null;
    }
}