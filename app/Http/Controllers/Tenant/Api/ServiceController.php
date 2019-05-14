<?php
namespace App\Http\Controllers\Tenant\Api;

use App\CoreFacturalo\Services\Dni\Dni;
use App\CoreFacturalo\Services\Extras\ExchangeRate;
use App\CoreFacturalo\Services\Ruc\Sunat;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\Province;
use Illuminate\Http\Request;
use App\CoreFacturalo\WS\Services\ConsultCdrService;
use App\CoreFacturalo\Facturalo;
use App\CoreFacturalo\WS\Validator\XmlErrorCodeProvider;
use App\CoreFacturalo\WS\Client\WsClient;

class ServiceController extends Controller
{
    protected $wsClient;

    public function consultCdrStatus(Request $request){


        $ruc = $request->ruc;
        $tipo = $request->tipo;
        $serie = $request->serie;
        $numero = $request->numero;
        // dd($ruc);
        // $data = $consult_cdr->getStatusCdr($ruc,$tipo,$serie,$numero);

        $wsdl = __DIR__.DIRECTORY_SEPARATOR.'Resources'.
                            DIRECTORY_SEPARATOR.'wsdl'.
                            DIRECTORY_SEPARATOR.'billConsultService.wsdl';

        dd($wsdl);
        $wsdl = "D:\laragon\www\multifacturalonew\app\CoreFacturalo\WS\Client\Resources\wsdl\billConsultService.wsdl";
        $this->wsClient = new WsClient($wsdl);

        $consultCdrService = new ConsultCdrService();
        $consultCdrService->setClient($this->wsClient);
        // dd($consultCdrService->setClient($this->wsClient));
        $consultCdrService->setCodeProvider(new XmlErrorCodeProvider());
        $res = $consultCdrService->getStatusCdr($ruc,$tipo,$serie,$numero);
        if(!$res->isSuccess()) {
            throw new \Exception("Code: {$res->getError()->getCode()}; Description: {$res->getError()->getMessage()}");
        } else {
            $cdrResponse = $res->getCdrResponse();
            $this->uploadFile($res->getCdrZip(), 'cdr');
            $this->updateState(self::ACCEPTED);
            $this->response = [
                'sent' => true,
                'code' => $cdrResponse->getCode(),
                'description' => $cdrResponse->getDescription(),
                'notes' => $cdrResponse->getNotes()
            ];
        }

        dd($res);

    }


    public function ruc($number)
    {
        $service = new Sunat();
        $res = $service->get($number);
        if ($res) {
            $province_id = Province::idByDescription($res->provincia);
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
                    'province_id' => $province_id,
                    'district' => ($res->distrito)?:'LIMA',
                    'district_id' => District::idByDescription($res->distrito, $province_id),
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

    public function exchangeRateTest($date)
    {
        $sale = 1;
        if($date <= now()->format('Y-m-d')) {
            $ex_rate = \App\Models\Tenant\ExchangeRate::where('date', $date)->first();
            if ($ex_rate) {
                $sale = $ex_rate->sale;
            } else {
                $exchange_rate = new ExchangeRate();
                $res = $exchange_rate->searchDate($date);
                if ($res) {
                    $ex_rate = \App\Models\Tenant\ExchangeRate::create([
                        'date' => $date,
                        'date_original' => $res['date_data'],
                        'purchase' => $res['data']['purchase'],
                        'purchase_original' => $res['data']['purchase'],
                        'sale' => $res['data']['sale'],
                        'sale_original' => $res['data']['sale']
                    ]);
                    $sale = $ex_rate->sale;
                }
            }
        }
        return [
            'date' => $date,
            'sale' => $sale
        ];
    }
}