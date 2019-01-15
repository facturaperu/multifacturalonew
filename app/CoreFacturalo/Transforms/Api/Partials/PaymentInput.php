<?php

namespace App\CoreFacturalo\Transforms\Api\Partials;

class PaymentInput
{
    public static function transform($inputs, $isWeb)
    {
        if($isWeb) {
            $payments = array_key_exists('payments', $inputs)?$inputs['payments']:[];
        } else {
            $payments = array_key_exists('pagos', $inputs)?$inputs['pagos']:[];
        }

        if(count($payments) === 0) {
            return null;
        }

        $transform_payments = [];
        foreach ($payments as $row)
        {
            if($isWeb) {
                $date_of_payment = $row['date_of_payment'];
                $total_payment = $row['total_payment'];
                $currency_type_id = $row['currency_type_id'];
            } else {
                $date_of_payment = $row['fecha_de_pago'];
                $total_payment = $row['total_pago'];
                $currency_type_id = $row['codigo_tipo_moneda'];
            }

            $transform_payments[] = [
                'date_of_payment' => $date_of_payment,
                'total_payment' => $total_payment,
                'currency_type_id' => $currency_type_id
            ];
        }

        return $transform_payments;
    }
}