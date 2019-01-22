<?php

namespace App\CoreFacturalo\Inputs\Dispatches\Partials;

class DriverInput
{
    public static function set($inputs)
    {
        if(array_key_exists('driver', $inputs)) {
            $driver = $inputs['driver'];
            $identity_document_type_id = $driver['identity_document_type_id'];
            $number = $driver['number'];

            return [
                'identity_document_type_id' => $identity_document_type_id,
                'number' => $number,
            ];
        }
        return null;
    }
}