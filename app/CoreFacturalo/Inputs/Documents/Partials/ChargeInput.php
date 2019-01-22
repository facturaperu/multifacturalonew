<?php

namespace App\CoreFacturalo\Inputs\Documents\Partials;

class ChargeInput
{
    public static function set($inputs)
    {
        if(array_key_exists('charges', $inputs)) {
            if($inputs['charges']) {
                $charges = [];
                foreach ($inputs['charges'] as $row) {
                    $code = $row['code'];
                    $description = $row['description'];
                    $factor = $row['factor'];
                    $amount = $row['amount'];
                    $base = $row['base'];

                    $charges[] = [
                        'code' => $code,
                        'description' => $description,
                        'factor' => $factor,
                        'amount' => $amount,
                        'base' => $base,
                    ];
                }
                return $charges;
            }
        }
        return null;
    }
}