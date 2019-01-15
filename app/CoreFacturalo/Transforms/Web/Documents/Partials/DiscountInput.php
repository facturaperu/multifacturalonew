<?php

namespace App\CoreFacturalo\Transforms\Web\Documents\Partials;

class DiscountInput
{
    public static function transform($inputs)
    {
        $discounts = array_key_exists('discounts', $inputs)?$inputs['discounts']:null;

        if(is_null($discounts)) {
            return null;
        }

        $transform_discounts = [];
        foreach ($discounts as $row)
        {
            $code = $row['discount_type_id'];
            $description = $row['description'];
            $factor = $row['factor'];
            $amount = $row['amount'];
            $base = $row['base'];

            $transform_discounts[] = [
                'code' => $code,
                'description' => $description,
                'base' => $base,
                'factor' => $factor,
                'amount' => $amount,
            ];
        }

        return $transform_discounts;
    }
}