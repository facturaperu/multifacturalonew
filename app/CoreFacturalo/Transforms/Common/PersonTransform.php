<?php

namespace App\CoreFacturalo\Transforms\Common;

use App\CoreFacturalo\Transforms\Functions;

class PersonTransform
{
    public static function transform($person)
    {
        return [
            'identity_document_type_id' => $person['codigo_tipo_documento_identidad'],
            'number' => $person['numero_documento'],
            'name' => $person['apellidos_y_nombres_o_razon_social'],
            'trade_name' => Functions::valueKeyInArray($person, 'nombre_comercial'),
            'country_id' => Functions::valueKeyInArray($person, 'codigo_pais'),
            'district_id' => Functions::valueKeyInArray($person, 'ubigeo'),
            'address' => Functions::valueKeyInArray($person, 'direccion'),
            'email' => Functions::valueKeyInArray($person, 'correo_electronico'),
            'telephone' => Functions::valueKeyInArray($person, 'telefono'),
        ];
    }
}