<?php

namespace App\CoreFacturalo\Inputs\Dispatches\Partials;

class OriginInput
{
    public static function set($inputs)
    {
        if(array_key_exists('origin', $inputs)) {
            $origin = $inputs['origin'];
            $location_id = $origin['location_id'];
            $address = $origin['address'];

            return [
                'location_id' => $location_id,
                'address' => $address,
            ];
        }
        return null;
    }
}