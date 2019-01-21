<?php

namespace App\CoreFacturalo\Inputs\Dispatches\Partials;

class DeliveryInput
{
    public static function set($inputs)
    {
        if(key_exists('delivery', $inputs)) {
            $delivery = $inputs['delivery'];
            $location_id = $delivery['location_id'];
            $address = $delivery['address'];

            return [
                'location_id' => $location_id,
                'address' => $address,
            ];
        }
        return null;
    }
}