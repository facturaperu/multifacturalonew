<?php

namespace App\CoreFacturalo\Transforms\Web\Documents\Partials;

class PrepaymentInput
{
    public static function transform($inputs)
    {
        $prepayments = array_key_exists('prepayments', $inputs)?$inputs['prepayments']:null;

        if(is_null($prepayments)) {
            return null;
        }

        $transform_prepayments = [];
        foreach ($prepayments as $row)
        {
            $document_type_id = $row['document_type_id'];
            $number = $row['number'];
            $amount = $row['amount'];

            $transform_prepayments[] = [
                'document_type_id' => $document_type_id,
                'number' => $number,
                'amount' => $amount
            ];
        }

        return $transform_prepayments;
    }
}