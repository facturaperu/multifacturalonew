<?php

namespace App\CoreFacturalo\Transforms\TransformWeb\Documents\Partials;

class PerceptionInput
{
    public static function transform($inputs)
    {
        $perception = array_key_exists('perception', $inputs)?$inputs['perception']:null;

        if(is_null($perception)) {
            return null;
        }

        $code = $perception['code'];
        $percentage = $perception['percentage'];
        $base = $perception['base'];
        $amount = $perception['amount'];

        return [
            'code' => $code,
            'base' => $base,
            'percentage' => $percentage,
            'amount' => $amount
        ];
    }
}