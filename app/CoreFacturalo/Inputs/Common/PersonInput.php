<?php

namespace App\CoreFacturalo\Inputs\Common;

use App\Models\Tenant\Person;

class PersonInput
{
    public static function set($inputs, $type, $service)
    {
        if($service === 'api') {
            $person_id = self::updateOrCreatePerson($inputs[$type], $type);
        } else {
            $person_id = $inputs['person_id'];
        }

        $person = Person::find($person_id);

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
                    'description' => $person->department->description,
                ],
                'province_id' => $person->province_id,
                'province' => [
                    'id' => $person->province_id,
                    'description' => $person->province->description,
                ],
                'district_id' => $person->district_id,
                'district' => [
                    'id' => $person->district_id,
                    'description' => $person->district->description,
                ],
                'address' => $person->address,
                'email' => $person->email,
                'telephone' => $person->telephone,
            ]
        ];
    }

    public static function updateOrCreatePerson($data, $type)
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

        return $person->id;
    }
}