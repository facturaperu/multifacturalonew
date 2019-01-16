<?php

namespace App\CoreFacturalo\Transforms\TransApi\Dispatches\Partials;

class DriverInput
{
    public static function transform($inputs)
    {
        $driver = array_key_exists('chofer', $inputs)?$inputs['chofer']:null;

        if(!$driver) {
            return null;
        }

        $identity_document_type_id = $driver['codigo_tipo_documento_identidad'];
        $number = $driver['numero_documento'];

        $transform_driver = [
            'identity_document_type_id' => $identity_document_type_id,
            'number' => $number,
        ];

        return $transform_driver;
    }
}