<?php

namespace App\CoreFacturalo\Transforms\Api\Partials;

class DetractionInput
{
    public static function transform($inputs)
    {
        $detraction = array_key_exists('detraccion', $inputs)?$inputs['detraccion']:null;

        if(is_null($detraction)) {
            return null;
        }

        $code = $detraction['codigo'];
        $percentage = $detraction['porcentaje'];
        $amount = $detraction['monto'];
        $payment_method_id = $detraction['codigo_metodo_pago'];
        $bank_account = $detraction['cuenta_bancaria'];

        return [
            'code' => $code,
            'percentage' => $percentage,
            'amount' => $amount,
            'payment_method_id' => $payment_method_id,
            'bank_account' => $bank_account,
        ];
    }
}