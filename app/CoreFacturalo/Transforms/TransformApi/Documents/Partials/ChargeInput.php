<?php

namespace App\CoreFacturalo\Transforms\TransformApi\Documents\Partials;

class ChargeInput
{
    public static function transform($inputs)
    {
        $charges = array_key_exists('cargos', $inputs)?$inputs['cargos']:null;

        if(is_null($charges)) {
            return null;
        }

        $transform_charges = [];
        foreach ($charges as $row)
        {
            $code = $row['codigo'];
            $description = $row['descripcion'];
            $factor = $row['factor'];
            $amount = $row['monto'];
            $base = $row['base'];

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