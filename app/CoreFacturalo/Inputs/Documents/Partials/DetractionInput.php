<?php

namespace App\CoreFacturalo\Inputs\Documents\Partials;

class DetractionInput
{
    public static function set($inputs)
    {
        if(array_key_exists('detraction', $inputs)) {
            if($inputs['detraction']) {
                $detraction = $inputs['detraction'];
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
        return null;
    }
}