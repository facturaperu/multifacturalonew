<?php

namespace App\CoreFacturalo\Inputs\Documents\Partials;

class PerceptionInput
{
    public static function set($inputs)
    {
        if(key_exists('perception', $inputs)) {
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
        return null;
    }
}