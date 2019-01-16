<?php

namespace App\CoreFacturalo\Transforms\TransApi\Common;

class PaymentInput
{
    public static function transform($inputs)
    {
        $payments = array_key_exists('pagos', $inputs)?$inputs['pagos']:[];

        if(count($payments) === 0) {
            return null;
        }

        $transform_payments = [];
        foreach ($payments as $row)
        {
            $date_of_payment = $row['fecha_de_pago'];
            $total_payment = $row['total_pago'];
            $currency_type_id = $row['codigo_tipo_moneda'];

            $transform_payments[] = [
                'date_of_payment' => $date_of_payment,
                'total_payment' => $total_payment,
                'currency_type_id' => $currency_type_id
            ];
        }

        return $transform_payments;
    }
}