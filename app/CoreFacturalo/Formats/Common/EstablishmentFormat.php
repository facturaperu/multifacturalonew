<?php

namespace App\CoreFacturalo\Formats\Common;

use App\Models\Tenant\Establishment;

class EstablishmentFormat
{
    public static function format($establishment_id)
    {
        $establishment = Establishment::find($establishment_id);

        return [
            'establishment_id' => $establishment->id,
            'establishment' => [
                'country_id' => $establishment->country_id,
                'country' => [
                    'id' => $establishment->country_id,
                    'description' => $establishment->country->description,
                ],
                'department_id' => $establishment->department_id,
                'department' => [
                    'id' => $establishment->department_id,
                    'description' => $establishment->department->description,
                ],
                'province_id' => $establishment->province_id,
                'province' => [
                    'id' => $establishment->province_id,
                    'description' => $establishment->province->description,
                ],
                'district_id' => $establishment->district_id,
                'district' => [
                    'id' => $establishment->district_id,
                    'description' => $establishment->district->description,
                ],
                'urbanization' => $establishment->urbanization,
                'address' => $establishment->address,
                'email' => $establishment->email,
                'telephone' => $establishment->telephone,
                'code' => $establishment->code,
            ]
        ];
    }
}