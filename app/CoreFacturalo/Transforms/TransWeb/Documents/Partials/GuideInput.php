<?php

namespace App\CoreFacturalo\Transforms\TransWeb\Documents\Partials;

class GuideInput
{
    public static function transform($inputs)
    {
        $guides = array_key_exists('guides', $inputs)?$inputs['guides']:null;

        if(is_null($guides)) {
            return null;
        }

        $transform_guides = [];
        foreach ($guides as $row)
        {
            $document_type_id = $row['document_type_id'];
            $number = $row['number'];

            $transform_guides[] = [
                'document_type_id' => $document_type_id,
                'number' => $number,
            ];
        }

        return $transform_guides;
    }
}