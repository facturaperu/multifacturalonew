<?php

namespace App\CoreFacturalo\Transforms\Api\Dispatches\Partials;

class DriverTransform
{
    public static function transform($inputs)
    {
        $driver = null;
        if(key_exists('chofer', $inputs)) {
            $driver = $inputs['chofer'];

            return [
                'identity_document_type_id' => $driver['codigo_tipo_documento_identidad'],
                'number' => $driver['numero_documento']
            ];
        }
        return null;
    }
}