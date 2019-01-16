<?php

namespace App\CoreFacturalo\Transforms\Api\Partials;

class DiscountInput
{
    public static function transform($inputs)
    {
        $discounts = array_key_exists('descuentos', $inputs)?$inputs['descuentos']:null;

        if(is_null($discounts)) {
            return null;
        }

        $transform_discounts = [];
        foreach ($discounts as $row)
        {
            $code = $row['codigo'];
            $description = $row['descripcion'];
            $factor = $row['factor'];
            $amount = $row['monto'];
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