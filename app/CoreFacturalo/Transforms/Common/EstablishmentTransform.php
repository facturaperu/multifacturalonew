<?php

namespace App\CoreFacturalo\Transforms\Common;

class EstablishmentTransform
{
    public static function transform($establishment)
    {
        return  [
            'code' => $establishment['codigo_del_domicilio_fiscal']
        ];
    }
}