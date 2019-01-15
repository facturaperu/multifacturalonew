<?php

namespace App\CoreFacturalo\Transforms\Web\Documents\Partials;

use App\Models\Tenant\Person;

class PersonInput
{
    public static function transform($inputs)
    {
        $person = Person::find($inputs['customer_id']);

        return [
            'customer_id' => $person->id,
            'customer' => [
                'identity_document_type_code' => $person->identity_document_type->code,
                'number' => $person->number,
                'name' => $person->name,
                'trade_name' => $person->trade_name,
                'country_code' => $person->country_id,
                'department_code' => $person->department_code,
                'province_code' => $person->province_code,
                'district_code' => $person->district_code,
                'address' => $person->address,
                'email' => $person->email,
                'telephone' => $person->telephone,
            ]
        ];
    }
}