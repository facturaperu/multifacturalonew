<?php

namespace App\CoreFacturalo\Transforms\Api\Partials;

class PerceptionInput
{
    public static function transform($inputs)
    {
        $perception = array_key_exists('percepcion', $inputs)?$inputs['percepcion']:null;

        if(is_null($perception)) {
            return null;
        }

        $code = $perception['codigo'];
        $percentage = $perception['porcentaje'];
        $amount = $perception['monto'];
        $base = $perception['base'];

        return [
            'code' => $code,
            'base' => $base,
            'percentage' => $percentage,
            'amount' => $amount
        ];
    }
}