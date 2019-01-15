<?php

namespace App\CoreFacturalo\Transforms\Web\Documents\Partials;

class ChargeInput
{
    public static function transform($inputs)
    {
        $charges = array_key_exists('charges', $inputs)?$inputs['charges']:null;

        if(is_null($charges)) {
            return null;
        }

        $transform_charges = [];
        foreach ($charges as $row)
        {
            $code = $row['charge_type_id'];
            $description = $row['description'];
            $factor = $row['factor'];
            $amount = $row['amount'];
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