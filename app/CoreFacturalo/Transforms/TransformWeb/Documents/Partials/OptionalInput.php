<?php

namespace App\CoreFacturalo\Transforms\TransformWeb\Documents\Partials;

class OptionalInput
{
    public static function transform($inputs)
    {
        $optional = array_key_exists('optional', $inputs)?$inputs['optional']:null;

            $observations = $optional['observations'];
            $method_payment = $optional['method_payment'];
            $salesman = $optional['salesman'];
            $box_number = $optional['box_number'];
            $format_pdf = array_key_exists('format_pdf', $optional)?$optional['format_pdf']:'a4';

        return [
            'observations' => $observations,
            'method_payment' => $method_payment,
            'salesman' => $salesman,
            'box_number' => $box_number,
            'format_pdf' => $format_pdf
        ];
    }
}