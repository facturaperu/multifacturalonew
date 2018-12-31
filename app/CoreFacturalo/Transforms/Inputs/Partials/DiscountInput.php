<?php

namespace App\CoreFacturalo\Transforms\Inputs\Partials;

class DiscountInput
{
    public static function transform($inputs, $isWeb)
    {
        if($isWeb) {
            $discounts = array_key_exists('discounts', $inputs)?$inputs['discounts']:null;
        } else {
            $discounts = array_key_exists('descuentos', $inputs)?$inputs['descuentos']:null;
        }

        if(is_null($discounts)) {
            return null;
        }

        $transform_discounts = [];
        foreach ($discounts as $row)
        {
            if($isWeb) {
                $code = $row['code'];
                $description = $row['description'];
                $factor = $row['factor'];
                $amount = $row['amount'];
                $base = $row['base'];
            } else {
                $code = $row['codigo'];
                $description = $row['descripcion'];
                $factor = $row['factor'];
                $amount = $row['monto'];
                $base = $row['base'];
            }

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