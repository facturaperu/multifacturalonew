<?php

namespace App\CoreFacturalo\Transforms\TransformWeb\Common;

class PaymentInput
{
    public static function transform($inputs)
    {
        $payments = array_key_exists('payments', $inputs)?$inputs['payments']:[];

        if(count($payments) === 0) {
            return null;
        }

        $transform_payments = [];
        foreach ($payments as $row)
        {
            $date_of_payment = $row['date_of_payment'];
            $total_payment = $row['total_payment'];
            $currency_type_id = $row['currency_type_id'];

            $transform_payments[] = [
                'date_of_payment' => $date_of_payment,
                'total_payment' => $total_payment,
                'currency_type_id' => $currency_type_id
            ];
        }

        return $transform_payments;
    }
}