<?php

namespace App\CoreFacturalo\Transforms\Api\Partials;

use App\Models\Tenant\Establishment;

class EstablishmentInput
{
    public static function transform($inputs, $isWeb)
    {
        if($isWeb) {
            $establishment = Establishment::find($inputs['establishment_id']);
        } else {
            $establishment_inputs = $inputs['datos_del_emisor'];
            $code = $establishment_inputs['codigo_del_domicilio_fiscal'];
            $establishment = Establishment::where('code', $code)->first();
        }

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