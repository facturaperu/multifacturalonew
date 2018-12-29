<?php

namespace App\CoreFacturalo\Transforms\Inputs\Partials;

class ChargeInput
{
    public static function transform($inputs)
    {
        $charges = null;
        if(key_exists('cargos', $inputs)) {
            foreach ($inputs['cargos'] as $row)
            {
                $code = $row['codigo'];
                $description = $row['descripcion'];
                $factor = $row['factor'];
                $amount = $row['monto'];
                $base = $row['base'];

                $charges[] = [
                    'code' => $code,
                    'description' => $description,
                    'factor' => $factor,
                    'amount' => $amount,
                    'base' => $base,
                ];
            }
        }

        return $charges;
    }
}