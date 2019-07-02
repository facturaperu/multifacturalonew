<?php

namespace Modules\Services\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Catalogs\Models\Department;
use Modules\Catalogs\Models\District;
use Modules\Catalogs\Models\Province;
use Modules\Services\Helpers\Dni\Dni;
use Modules\Services\Helpers\Ruc\Ruc;
use Modules\Services\Helpers\Ruc\Sunat;

class ServiceController extends Controller
{
    public function ruc($number)
    {
        $res = Ruc::search($number);

        return $res;
    }

    public function dni($number)
    {
        $res = Dni::search($number);

        return $res;
    }
}
