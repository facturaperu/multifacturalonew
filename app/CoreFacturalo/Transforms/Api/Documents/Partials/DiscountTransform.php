<?php

namespace App\CoreFacturalo\Transforms\Api\Documents\Partials;

class DiscountTransform
{
    public static function transform($inputs)
    {
        if(key_exists('descuentos', $inputs)) {
            $discounts = [];
            foreach ($inputs['descuentos'] as $row) {
                $discounts[] = [
                    'code' => $row['codigo'],
                    'description' => $row['descripcion'],
                    'factor' => $row['factor'],
                    'amount' => $row['monto'],
                    'base' =>  $row['base'],
                ];
            }

            return $discounts;
        }
        return null;
    }
}