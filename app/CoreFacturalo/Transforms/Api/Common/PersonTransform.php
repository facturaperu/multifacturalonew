<?php

namespace App\CoreFacturalo\Transforms\Api\Common;

use App\CoreFacturalo\Transforms\Api\FunctionsTransform;
use App\Models\Tenant\Person;

class PersonTransform
{
    public static function transform($inputs, $type)
    {

        $data = [
            'identity_document_type_id' => $inputs['codigo_tipo_documento_identidad'],
            'number' => $inputs['numero_documento'],
            'name' => $inputs['apellidos_y_nombres_o_razon_social'],
            'trade_name' => FunctionsTransform::valueKeyInArray($inputs, 'nombre_comercial'),
            'country_id' => FunctionsTransform::valueKeyInArray($inputs, 'codigo_pais'),
            'district_id' => FunctionsTransform::valueKeyInArray($inputs, 'ubigeo'),
            'address' => FunctionsTransform::valueKeyInArray($inputs, 'direccion'),
            'email' => FunctionsTransform::valueKeyInArray($inputs, 'correo_electronico'),
            'telephone' => FunctionsTransform::valueKeyInArray($inputs, 'telefono'),
        ];
        $person = self::updateOrCreatePerson($data, $type);
        return $person->id;
    }

    private static function updateOrCreatePerson($data, $type)
    {
        $district_id = $data['district_id'];
        $province_id = ($district_id)?substr($district_id, 0 ,4):null;
        $department_id = ($district_id)?substr($district_id, 0 ,2):null;

        $person = Person::updateOrCreate(
            [
                'type' => $type,
                'identity_document_type_id' => $data['identity_document_type_id'],
                'number' => $data['number'],
            ],
            [
                'name' => $data['name'],
                'trade_name' => $data['trade_name'],
                'country_id' => $data['country_id'],
                'department_id' => $department_id,
                'province_id' => $province_id,
                'district_id' => $district_id,
                'address' => $data['address'],
                'email' => $data['email'],
                'telephone' => $data['telephone'],
            ]
        );

        return $person;
    }
}