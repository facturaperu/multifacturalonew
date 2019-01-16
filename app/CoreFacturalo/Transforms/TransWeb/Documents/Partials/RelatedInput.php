<?php

namespace App\CoreFacturalo\Transforms\TransWeb\Documents\Partials;

class RelatedInput
{
    public static function transform($inputs)
    {
        $related = array_key_exists('related', $inputs)?$inputs['related']:null;

        if(is_null($related)) {
            return null;
        }

        $transform_related = [];
        foreach ($related as $row)
        {
            $document_type_id = $row['document_type_id'];
            $number = $row['number'];
            $amount = $row['amount'];

            $transform_related[] = [
                'document_type_id' => $document_type_id,
                'number' => $number,
                'amount' => $amount
            ];
        }

        return $transform_related;
    }
}