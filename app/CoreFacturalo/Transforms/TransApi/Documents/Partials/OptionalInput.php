<?php

namespace App\CoreFacturalo\Transforms\TransApi\Documents\Partials;

class OptionalInput
{
    public static function transform($inputs)
    {
        $optional = array_key_exists('extras', $inputs)?$inputs['extras']:[];

        $observations = array_key_exists('observaciones', $optional)?$optional['observaciones']:null;
        $method_payment = array_key_exists('forma_de_pago', $optional)?$optional['forma_de_pago']:null;
        $salesman = array_key_exists('vendedor', $optional)?$optional['vendedor']:null;
        $box_number = array_key_exists('caja', $optional)?$optional['caja']:null;

        return [
            'observations' => $observations,
            'method_payment' => $method_payment,
            'salesman' => $salesman,
            'box_number' => $box_number,
        ];
    }
}