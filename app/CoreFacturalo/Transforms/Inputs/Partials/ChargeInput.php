<?php

namespace App\CoreFacturalo\Transforms\Inputs\Partials;

class ChargeInput
{
    public static function transform($inputs, $isWeb)
    {
        if($isWeb) {
            $charges = array_key_exists('charges', $inputs)?$inputs['charges']:null;
        } else {
            $charges = array_key_exists('cargos', $inputs)?$inputs['cargos']:null;
        }

        if(is_null($charges)) {
            return null;
        }

        $transform_charges = [];
        foreach ($charges as $row)
        {
            if($isWeb) {
                $code = $row['code'];
                $description = $row['description'];
                $factor = $row['factor'];
                $amount = $row['amount'];
                $base = $row['base'];
            } else {
                $code = $row['codigo'];
                $description = $row['descripcion'];
                $factor = $row['factor'];
                $amount = $row['monto'];
                $base = $row['base'];
            }

            $transform_charges[] = [
                'code' => $code,
                'description' => $description,
                'base' => $base,
                'factor' => $factor,
                'amount' => $amount,
            ];
        }

        return $transform_charges;
    }
}