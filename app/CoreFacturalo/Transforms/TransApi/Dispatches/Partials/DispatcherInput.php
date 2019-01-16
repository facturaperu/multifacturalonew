<?php

namespace App\CoreFacturalo\Transforms\TransApi\Dispatches\Partials;

class DispatcherInput
{
    public static function transform($inputs)
    {
        $dispatcher = array_key_exists('transportista', $inputs)?$inputs['transportista']:null;

        if(!$dispatcher) {
            return null;
        }

        $identity_document_type_id = $dispatcher['codigo_tipo_documento_identidad'];
        $number = $dispatcher['numero_documento'];
        $name = $dispatcher['apellidos_y_nombres_o_razon_social'];
        $license_plate = $dispatcher['placa'];

        $transform_dispatcher[] = [
            'identity_document_type_id' => $identity_document_type_id,
            'number' => $number,
            '$name' => $name,
            'license_plate' => $license_plate,
        ];

        return $transform_dispatcher;
    }
}