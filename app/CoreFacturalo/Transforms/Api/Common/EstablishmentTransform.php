<?php

namespace App\CoreFacturalo\Transforms\Api\Common;

use App\Models\Tenant\Establishment;
use Exception;

class EstablishmentTransform
{
    public static function transform($inputs)
    {
        $data = [
            'code' => $inputs['codigo_del_domicilio_fiscal'],
        ];
        $establishment = self::findEstablishmentByCode($data);

        return $establishment->id;
    }

    private static function findEstablishmentByCode($data)
    {
        $establishment = Establishment::where('code', $data['code'])->first();
        if(!$establishment) {
            throw new Exception("El código ingresado del establecimiento es incorrecto.");
        }
        return $establishment;
    }
}