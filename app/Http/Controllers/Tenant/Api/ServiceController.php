<?php
namespace App\Http\Controllers\Tenant\Api;

use App\Core\Cpe\ConsultCdrService;
use App\Core\Services\Dni\Dni;
use App\Core\Services\Extras\ValidateCpe;
use App\Core\Services\Ruc\Sunat;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Department;
use App\Models\Tenant\District;
use App\Models\Tenant\Document;
use App\Models\Tenant\Province;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function ruc($number)
    {
        $service = new Sunat();
        $res = $service->get($number);
        if ($res) {
            return [
                'success' => true,
                'data' => [
                    'name' => $res->razonSocial,
                    'trade_name' => $res->nombreComercial,
                    'address' => $res->direccion,
                    'phone' => implode(' / ', $res->telefonos),
                    'department' => ($res->departamento)?:'LIMA',
                    'department_id' => Department::idByDescription($res->departamento),
                    'province' => ($res->provincia)?:'LIMA',
                    'province_id' => Province::idByDescription($res->provincia),
                    'district' => ($res->distrito)?:'LIMA',
                    'district_id' => District::idByDescription($res->distrito),
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => $service->getError()
            ];
        }
    }

    public function dni($number)
    {
        $res = Dni::search($number);

        return $res;
    }

    public function validateCpe(Request $request)
    {
        $validateCpe = new ValidateCpe();
        $documents = Document::whereIn('external_id', $request->input('documents'))->get();
        $res = [];
        foreach($documents as $document) {
            $res[] = $validateCpe->search($document->document_type_code, $document->series, $document->number, $document->date_of_issue);
        }
        return $res;
    }

    public function consultStatus($documents)
    {
        $consultCdrService = new ConsultCdrService();
        $res = $consultCdrService->getStatus('01', 'F001', 5);

        return $res;
    }

    public function consultCdrStatus($documents)
    {
        $consultCdrService = new ConsultCdrService();
        $res = $consultCdrService->getStatusCdr('01', 'F001', 4);

        return $res;
    }
}