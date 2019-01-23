<?php

namespace App\CoreFacturalo\Transforms\Api\Dispatches\Partials;

class DeliveryTransform
{
    public static function transform($inputs)
    {
        if(key_exists('direccion_llegada', $inputs)) {
            $delivery = $inputs['direccion_llegada'];

            return [
                'location_id' => $delivery['ubigeo'],
                'address' => $delivery['direccion'],
            ];
        }
        return null;
    }
}