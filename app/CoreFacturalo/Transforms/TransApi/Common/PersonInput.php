<?php

namespace App\CoreFacturalo\Transforms\TransApi\Common;

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

        return [
            'person_id' => $person->id,
            'person' => [
                'identity_document_type_id' => $person->identity_document_type_id,
                'identity_document_type' => [
                    'id' => $person->identity_document_type_id,
                    'description' => $person->identity_document_type->description,
                ],
                'number' => $person->number,
                'name' => $person->name,
                'trade_name' => $person->trade_name,
                'country_id' => $person->country_id,
                'country' => [
                    'id' => $person->country_id,
                    'description' => $person->country->description,
                ],
                'department_id' => $person->department_id,
                'department' => [
                    'id' => $person->department_id,
                    'description' => optional($person->department)->description,
                ],
                'province_id' => $person->province_id,
                'province' => [
                    'id' => $person->province_id,
                    'description' => optional($person->province)->description,
                ],
                'district_id' => $person->district_id,
                'district' => [
                    'id' => $person->district_id,
                    'description' => optional($person->district)->description,
                ],
                'address' => $person->address,
                'email' => $person->email,
                'telephone' => $person->telephone,
            ]
        ];
    }
}