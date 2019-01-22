<?php

namespace App\CoreFacturalo\Transforms\Documents\Partials;

class ChargeTransform
{
    public static function transform($inputs)
    {
        if(key_exists('cargos', $inputs)) {
            $charges = [];
            foreach ($inputs['cargos'] as $row)
            {
                $charges[] = [
                    'code' => $row['codigo'],
                    'description' => $row['descripcion'],
                    'factor' => $row['factor'],
                    'amount' => $row['monto'],
                    'base' =>  $row['base'],
                ];
            }

            return $charges;
        }
        return null;
    }
}