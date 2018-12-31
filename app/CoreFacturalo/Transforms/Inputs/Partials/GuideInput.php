<?php

namespace App\CoreFacturalo\Transforms\Inputs\Partials;

class GuideInput
{
    public static function transform($inputs, $isWeb)
    {
        if($isWeb) {
            $guides = array_key_exists('guides', $inputs)?$inputs['guides']:null;
        } else {
            $guides = array_key_exists('guias', $inputs)?$inputs['guias']:null;
        }

        if(is_null($guides)) {
            return null;
        }

        $transform_guides = [];
        foreach ($guides as $row)
        {
            if($isWeb) {
                $document_type_id = $row['document_type_id'];
                $number = $row['number'];
            } else {
                $number = $row['numero'];
                $document_type_id = $row['tipo_de_documento'];
            }

            $transform_guides[] = [
                'document_type_id' => $document_type_id,
                'number' => $number,
            ];
        }

        return $transform_guides;
    }
}