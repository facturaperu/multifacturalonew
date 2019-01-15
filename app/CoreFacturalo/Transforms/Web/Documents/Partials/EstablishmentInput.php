<?php

namespace App\CoreFacturalo\Transforms\Web\Documents\Partials;

use App\Models\Tenant\Establishment;

class EstablishmentInput
{
    public static function transform($inputs)
    {
        $establishment = Establishment::find($inputs['establishment_id']);

        return [
            'establishment_id' => $establishment->id,
            'establishment' => [
                'country_code' => $establishment->country_id,
                'department_code' => $establishment->department_id,
                'province_code' => $establishment->province_id,
                'district_code' => $establishment->district_id,
                'urbanization' => $establishment->urbanization,
                'address' => $establishment->address,
                'email' => $establishment->email,
                'telephone' => $establishment->telephone,
                'code' => $establishment->code,
            ]
        ];
    }
}