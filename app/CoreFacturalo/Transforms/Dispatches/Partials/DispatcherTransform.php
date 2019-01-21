<?php

namespace App\CoreFacturalo\Transforms\Dispatches\Partials;

class DispatcherTransform
{
    public static function transform($inputs)
    {
        if(key_exists('transportista', $inputs)) {
            $dispatcher = $inputs['transportista'];

            return [
                'identity_document_type_id' => $dispatcher['codigo_tipo_documento_identidad'],
                'number' => $dispatcher['numero_documento'],
                'name' => $dispatcher['apellidos_y_nombres_o_razon_social'],
            ];
        }
        return null;
    }
}