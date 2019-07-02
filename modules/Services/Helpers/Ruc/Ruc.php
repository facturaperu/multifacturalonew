<?php

namespace Modules\Services\Helpers\Ruc;

class Ruc
{
    public static function search($number)
    {
        if (strlen($number) !== 11) {
            return [
                'success' => false,
                'message' => 'El número de RUC ingresado es inválido.'
            ];
        }

        $res = SunatCloud::search($number);
        if ($res['success']) {
            return $res;
        }

        $res = (new Sunat)->search($number);
        return $res;
    }
}