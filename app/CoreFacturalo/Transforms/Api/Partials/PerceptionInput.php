<?php

namespace App\CoreFacturalo\Transforms\Api\Partials;

class PerceptionInput
{
    public static function transform($inputs, $isWeb)
    {
        if($isWeb) {
            $perception = array_key_exists('perception', $inputs)?$inputs['perception']:null;
        } else {
            $perception = array_key_exists('percepcion', $inputs)?$inputs['percepcion']:null;
        }

        if(is_null($perception)) {
            return null;
        }

        if($isWeb) {
            $code = $perception['code'];
            $percentage = $perception['percentage'];
            $base = $perception['base'];
            $amount = $perception['amount'];
        } else {
            $code = $perception['codigo'];
            $percentage = $perception['porcentaje'];
            $amount = $perception['monto'];
            $base = $perception['base'];
        }

        return [
            'code' => $code,
            'base' => $base,
            'percentage' => $percentage,
            'amount' => $amount
        ];
    }
}