<?php

namespace Modules\Services\Helpers\Dni;

class Dni
{
    public static function search($number)
    {
        if (strlen($number) !== 8) {
            return [
                'success' => false,
                'message' => 'El número de DNI ingresado es inválido.'
            ];
        }

        $res = Essalud::search($number);
        if ($res['success']) {
            return $res;
        }

        $res = ReniecCloud::search($number);
        if ($res['success']) {
            return $res;
        }

        $res = Jne::search($number);
        return $res;


    }
}