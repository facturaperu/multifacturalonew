<?php

namespace App\CoreFacturalo\Inputs\Documents\Partials;

class DiscountInput
{
    public static function set($inputs)
    {
        if(array_key_exists('discounts', $inputs)) {
            if($inputs['discounts']) {
                $discounts = [];
                foreach ($inputs['discounts'] as $row) {
                    $code = $row['code'];
                    $description = $row['description'];
                    $factor = $row['factor'];
                    $amount = $row['amount'];
                    $base = $row['base'];

                    $discounts[] = [
                        'code' => $code,
                        'description' => $description,
                        'factor' => $factor,
                        'amount' => $amount,
                        'base' => $base,
                    ];
                }
                return $discounts;
            }
        }
        return null;
    }
}