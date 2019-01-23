<?php

namespace App\CoreFacturalo\Transforms\Api\Documents\Partials;

class DetractionTransform
{
    public static function transform($inputs)
    {
        if(key_exists('detraccion', $inputs)) {
            $detraction = $inputs['detraccion'];
            return [
                'code' => $detraction['codigo'],
                'percentage' => $detraction['porcentaje'],
                'amount' => $detraction['monto'],
                'payment_method_id' => $detraction['codigo_metodo_pago'],
                'bank_account' => $detraction['cuenta_bancaria'],
            ];
        }
        return null;
    }
}