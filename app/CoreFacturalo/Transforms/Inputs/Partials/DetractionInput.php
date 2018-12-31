<?php

namespace App\CoreFacturalo\Transforms\Inputs\Partials;

class DetractionInput
{
    public static function transform($inputs, $isWeb)
    {
        if($isWeb) {
            $detraction = array_key_exists('detraction', $inputs)?$inputs['detraction']:null;
        } else {
            $detraction = array_key_exists('detraccion', $inputs)?$inputs['detraccion']:null;
        }

        if(is_null($detraction)) {
            return null;
        }

        if($isWeb) {
            $code = $detraction['code'];
            $percentage = $detraction['percentage'];
            $amount = $detraction['amount'];
            $payment_method_id = $detraction['payment_method_id'];
            $bank_account = $detraction['bank_account'];
        } else {
            $code = $detraction['codigo'];
            $percentage = $detraction['porcentaje'];
            $amount = $detraction['monto'];
            $payment_method_id = $detraction['codigo_metodo_pago'];
            $bank_account = $detraction['cuenta_bancaria'];
        }

        return [
            'code' => $code,
            'percentage' => $percentage,
            'amount' => $amount,
            'payment_method_id' => $payment_method_id,
            'bank_account' => $bank_account,
        ];
    }
}