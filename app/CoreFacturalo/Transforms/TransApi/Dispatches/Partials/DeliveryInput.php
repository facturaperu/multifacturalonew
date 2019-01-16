<?php

namespace App\CoreFacturalo\Transforms\TransApi\Dispatches\Partials;

class DeliveryInput
{
    public static function transform($inputs)
    {
        $origin = array_key_exists('direccion_llegada', $inputs)?$inputs['direccion_llegada']:null;

        if(!$origin) {
            return null;
        }

        $location_id = $origin['ubigeo'];
        $address = $origin['direccion'];

        $transform_origin = [
            'location_id' => $location_id,
            'address' => $address,
        ];

        return $transform_origin;
    }
}