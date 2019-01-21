<?php

namespace App\CoreFacturalo\Transforms\Documents\Partials;

class PerceptionTransform
{
    public static function transform($inputs)
    {
        if(key_exists('percepcion', $inputs)) {
            $perception = $inputs['percepcion'];

            return [
                'code' => $perception['codigo'],
                'percentage' => $perception['porcentaje'],
                'amount' => $perception['monto'],
                'base' => $perception['base'],
            ];
        }
        return null;
    }
}