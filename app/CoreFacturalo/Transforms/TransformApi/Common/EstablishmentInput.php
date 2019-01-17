<?php

namespace App\CoreFacturalo\Transforms\TransformApi\Common;

use App\Models\Tenant\Establishment;

class EstablishmentInput
{
    public static function transform($establishment_inputs)
    {
        $code = $establishment_inputs['codigo_del_domicilio_fiscal'];
        $establishment = Establishment::where('code', $code)->first();

        return $establishment->id;
    }
}