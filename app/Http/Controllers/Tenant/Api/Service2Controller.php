<?php

namespace App\Http\Controllers\Tenant\Api;

use Illuminate\Routing\Controller;
use Modules\Catalogs\Models\Department;
use Modules\Catalogs\Models\District;
use Modules\Catalogs\Models\Province;
use Modules\Services\Helpers\Dni\Dni;
use Modules\Services\Helpers\Ruc\Ruc;
use Modules\Services\Helpers\Ruc\Sunat;

class Service2Controller extends Controller
{
    public function ruc($number)
    {
//        dd($number);
        $res = Ruc::search($number);

        return $res;

//        $service = new Sunat();
//        $res = $service->get($number);
//        if ($res) {
//            $department_id = Department::idByDescription($res->departamento);
//            $province_id = Province::idByDescription($res->provincia);
//            $district_id = District::idByDescription($res->distrito, $province_id);
//            return [
//                'success' => true,
//                'data' => [
//                    'name' => $res->razonSocial,
//                    'trade_name' => $res->nombreComercial,
//                    'address' => $res->direccion,
//                    'phone' => implode(' / ', $res->telefonos),
//                    'department' => ($res->departamento)?:'LIMA',
//                    //'department_id' => Department::idByDescription($res->departamento),
//                    'province' => ($res->provincia)?:'LIMA',
////                    'province_id' => $province_id,
//                    'district' => ($res->distrito)?:'LIMA',
//                    'location_id' => [$department_id, $province_id, $district_id]
//                    //'district_id' => District::idByDescription($res->distrito, $province_id),
//                ]
//            ];
//        } else {
//            return [
//                'success' => false,
//                'message' => $service->getError()
//            ];
//        }
    }

    public function dni($number)
    {
        $res = Dni::search($number);

        return $res;
    }
}
