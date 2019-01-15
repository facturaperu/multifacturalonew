<?php

namespace App\CoreFacturalo\Transforms\Api\Partials;

class OptionalInput
{
    public static function transform($inputs, $isWeb)
    {
        if($isWeb) {
            $optional = array_key_exists('optional', $inputs)?$inputs['optional']:null;
        } else {
            $optional = array_key_exists('extras', $inputs)?$inputs['extras']:[];
        }

//        if(is_null($optional)) {
//            return null;
//        }

        if($isWeb) {
            $observations = $optional['observations'];
            $method_payment = $optional['method_payment'];
            $salesman = $optional['salesman'];
            $box_number = $optional['box_number'];
            $format_pdf = $optional['format_pdf'];
        } else {
            $observations = array_key_exists('observaciones', $optional)?$optional['observaciones']:null;
            $method_payment = array_key_exists('forma_de_pago', $optional)?$optional['forma_de_pago']:null;
            $salesman = array_key_exists('vendedor', $optional)?$optional['vendedor']:null;
            $box_number = array_key_exists('caja', $optional)?$optional['caja']:null;
            $format_pdf = array_key_exists('formato_pdf', $optional)?$optional['formato_pdf']:'a4';
        }

        return [
            'observations' => $observations,
            'method_payment' => $method_payment,
            'salesman' => $salesman,
            'box_number' => $box_number,
            'format_pdf' => $format_pdf
        ];
    }
}