<?php

namespace App\Core\Services\Dni;


class Dni
{
    public static function search($number)
    {
        $res = Essalud::search($number);
        if ($res['success']) {
            return $res;
        }

        $res = Jne::search($number);
        return $res;
    }
}