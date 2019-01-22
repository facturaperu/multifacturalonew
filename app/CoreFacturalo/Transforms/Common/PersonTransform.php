<?php

namespace App\CoreFacturalo\Transforms\Common;

use App\CoreFacturalo\Transforms\TransformFunctions;

class PersonTransform
{
    public static function transform($person)
    {
        return [
            'identity_document_type_id' => $person['codigo_tipo_documento_identidad'],
            'number' => $person['numero_documento'],
            'name' => $person['apellidos_y_nombres_o_razon_social'],
            'trade_name' => TransformFunctions::valueKeyInArray($person, 'nombre_comercial'),
            'country_id' => TransformFunctions::valueKeyInArray($person, 'codigo_pais'),
            'district_id' => TransformFunctions::valueKeyInArray($person, 'ubigeo'),
            'address' => TransformFunctions::valueKeyInArray($person, 'direccion'),
            'email' => TransformFunctions::valueKeyInArray($person, 'correo_electronico'),
            'telephone' => TransformFunctions::valueKeyInArray($person, 'telefono'),
        ];
    }
}