<?php

namespace App\CoreFacturalo\Inputs\Documents\Partials;

class PerceptionInput
{
    public static function set($inputs)
    {
        if(array_key_exists('perception', $inputs)) {
            if($inputs['perception']) {
                $perception = $inputs['perception'];
                $code = $perception['code'];
                $percentage = $perception['percentage'];
                $amount = $perception['amount'];
                $base = $perception['base'];

                return [
                    'code' => $code,
                    'percentage' => $percentage,
                    'amount' => $amount,
                    'base' => $base,
                ];
            }
        }
        return null;
    }
}