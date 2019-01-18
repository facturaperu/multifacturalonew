<?php

namespace App\CoreFacturalo\Transforms\TransformWeb\Documents\Partials;

class DetractionInput
{
    public static function transform($inputs)
    {
        $detraction = array_key_exists('detraction', $inputs)?$inputs['detraction']:null;

        if(is_null($detraction)) {
            return null;
        }

        $code = $detraction['code'];
        $percentage = $detraction['percentage'];
        $amount = $detraction['amount'];
        $payment_method_id = $detraction['payment_method_id'];
        $bank_account = $detraction['bank_account'];

        return [
            'code' => $code,
            'percentage' => $percentage,
            'amount' => $amount,
            'payment_method_id' => $payment_method_id,
            'bank_account' => $bank_account,
        ];
    }
}