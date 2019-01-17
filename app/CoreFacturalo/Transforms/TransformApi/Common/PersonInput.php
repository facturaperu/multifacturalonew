<?php

namespace App\CoreFacturalo\Transforms\TransformApi\Common;

use App\Models\Tenant\Person;

class PersonInput
{
    public static function transform($person_inputs)
    {
        $identity_document_type_id = $person_inputs['codigo_tipo_documento_identidad'];
        $number = $person_inputs['numero_documento'];
        $name = $person_inputs['apellidos_y_nombres_o_razon_social'];
        $trade_name = (array_key_exists('nombre_comercial', $person_inputs))?$person_inputs['nombre_comercial']:null;
        $country_id = (array_key_exists('codigo_pais', $person_inputs))?$person_inputs['codigo_pais']:'PE';
        $district_id = (array_key_exists('ubigeo', $person_inputs))?$person_inputs['ubigeo']:null;
        $province_id = ($district_id)?substr($district_id, 0 ,4):null;
        $department_id = ($district_id)?substr($district_id, 0 ,2):null;
        $address = (array_key_exists('direccion', $person_inputs))?$person_inputs['direccion']:null;
        $email = (array_key_exists('correo_electronico', $person_inputs))?$person_inputs['correo_electronico']:null;
        $telephone = (array_key_exists('telefono', $person_inputs))?$person_inputs['telefono']:null;

        $person = Person::updateOrCreate(
            [
                'identity_document_type_id' => $identity_document_type_id,
                'number' => $number
            ],
            [
                'name' => $name,
                'trade_name' => $trade_name,
                'country_id' => $country_id,
                'district_id' => $district_id,
                'province_id' => $province_id,
                'department_id' => $department_id,
                'address' => $address,
                'email' => $email,
                'telephone' => $telephone,
            ]
        );

        return $person->id;
    }
}