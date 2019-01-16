<?php

namespace App\CoreFacturalo\Transforms\Api\Partials;

class GuideInput
{
    public static function transform($inputs)
    {
        $guides = array_key_exists('guias', $inputs)?$inputs['guias']:null;

        if(is_null($guides)) {
            return null;
        }

        $transform_guides = [];
        foreach ($guides as $row)
        {
            $number = $row['numero'];
            $document_type_id = $row['tipo_de_documento'];

            $transform_guides[] = [
                'document_type_id' => $document_type_id,
                'number' => $number,
            ];
        }

        return $transform_guides;
    }
}