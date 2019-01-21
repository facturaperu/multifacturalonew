<?php

namespace App\CoreFacturalo\Transforms\Dispatches\Partials;

class OriginTransform
{
    public static function transform($inputs)
    {
        if(key_exists('direccion_partida', $inputs)) {
            $origin = $inputs['direccion_partida'];

            return [
                'location_id' => $origin['ubigeo'],
                'address' => $origin['direccion'],
            ];
        }
        return null;
    }
}