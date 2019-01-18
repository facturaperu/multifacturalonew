<?php

namespace App\CoreFacturalo\Transforms\TransformApi\Common;

use App\Models\Tenant\Catalogs\Country;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Models\Tenant\Catalogs\Province;

class PersonInput
{
    public static function transform($person)
    {
        $identity_document_type_id = $person['codigo_tipo_documento_identidad'];
        $number = $person['numero_documento'];
        $name = $person['apellidos_y_nombres_o_razon_social'];
        $trade_name = array_key_exists('nombre_comercial', $person)?$person['nombre_comercial']:null;
        $country_id = array_key_exists('codigo_pais', $person)?$person['codigo_pais']:null;
        $district_id = array_key_exists('ubigeo', $person)?$person['ubigeo']:null;
        $address = array_key_exists('direccion', $person)?$person['direccion']:null;
        $email = array_key_exists('correo_electronico', $person)?$person['correo_electronico']:null;
        $telephone = array_key_exists('telephone', $person)?$person['telefono']:null;

        $department_id = null;
        $province_id = null;

        if ($district_id) {
            $province_id = substr($district_id, 0 ,4);
            $department_id = substr($district_id, 0 ,2);
        }

        return [
            'identity_document_type_id' => $identity_document_type_id,
            'identity_document_type' => [
                'id' => $identity_document_type_id,
                'description' => IdentityDocumentType::find($identity_document_type_id)->description,
            ],
            'number' => $number,
            'name' => $name,
            'trade_name' => $trade_name,
            'country_id' => $country_id,
            'country' => [
                'id' => $country_id,
                'description' => Country::find($country_id)->description,
            ],
            'department_id' => $department_id,
            'department' => [
                'id' => $department_id,
                'description' => Department::find($department_id)->description,
            ],
            'province_id' => $province_id,
            'province' => [
                'id' => $province_id,
                'description' => Province::find($province_id)->description,
            ],
            'district_id' => $district_id,
            'district' => [
                'id' => $district_id,
                'description' => District::find($district_id)->description,
            ],
            'address' => $address,
            'email' => $email,
            'telephone' => $telephone,
        ];
    }
}