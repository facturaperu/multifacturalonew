<?php

namespace App\CoreFacturalo\Inputs\Documents\Partials;

class DiscountInput
{
    public static function set($inputs)
    {
        if(key_exists('discounts', $inputs)) {
            if($inputs['discounts']) {
                $discounts = [];
                foreach ($inputs['discounts'] as $row) {
                    $code = $row['code'];
                    $description = $row['description'];
                    $percentage = $row['percentage'];
                    $amount = $row['amount'];
                    $base = $row['base'];

                    $discounts[] = [
                        'code' => $code,
                        'description' => $description,
                        'percentage' => $percentage,
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