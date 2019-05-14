<?php
namespace App\Http\Controllers\Tenant\Api;

use App\CoreFacturalo\Services\Dni\Dni;
use App\CoreFacturalo\Services\Extras\ExchangeRate;
use App\CoreFacturalo\Services\Ruc\Sunat;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\Province;
use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use Illuminate\Http\Request;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\CoreFacturalo\WS\Services\ConsultCdrService;
use App\CoreFacturalo\Facturalo;
use App\CoreFacturalo\WS\Validator\XmlErrorCodeProvider;
use App\CoreFacturalo\WS\Client\WsClient;
use App\CoreFacturalo\WS\Services\SunatEndpoints;

class ServiceController extends Controller
{

    //codigo agregado

    protected $wsClient;
    protected $document;
    use StorageDocument;
    const ACCEPTED = '05';

    public function consultCdrStatus(Request $request){


        $ruc = $request->ruc;
        $tipo = $request->tipo;
        $serie = $request->serie;
        $numero = $request->numero;
        
        $this->document = Document::where([['soap_type_id','02'],['document_type_id',$tipo],['series',$serie],['number',$numero]])->first();

        // $wsdl = __DIR__.DIRECTORY_SEPARATOR.'Resources'.
        //                     DIRECTORY_SEPARATOR.'wsdl'.
        //                     DIRECTORY_SEPARATOR.'billConsultService.wsdl';

        //modificar dinamico
        // $wsdl = "/var/www/html/app/CoreFacturalo/WS/Client/Resources/wsdl/billConsultService.wsdl" //linux;
        
        $wsdl = "D:\laragon\www\multifacturalonew\app\CoreFacturalo\WS\Client\Resources\wsdl\billConsultService.wsdl";
        $company = Company::active();
        $username = $company->soap_username;
        $password = $company->soap_password;

        $this->wsClient = new WsClient($wsdl);
        $this->wsClient->setCredentials($username, $password);
        $this->wsClient->setService(SunatEndpoints::FE_CONSULTA_CDR.'?wsdl');

        $consultCdrService = new ConsultCdrService();
        $consultCdrService->setClient($this->wsClient);
        $consultCdrService->setCodeProvider(new XmlErrorCodeProvider());
        $res = $consultCdrService->getStatusCdr($ruc,$tipo,$serie,$numero);

        if(!$res->isSuccess()) {
            throw new \Exception("Code: {$res->getError()->getCode()}; Description: {$res->getError()->getMessage()}");
        } else {
            $cdrResponse = $res->getCdrResponse();
            $this->uploadFile($res->getCdrZip(), 'cdr');
            $this->updateState(self::ACCEPTED);
            return [
                'sent' => true,
                'code' => $cdrResponse->getCode(),
                'description' => $cdrResponse->getDescription(),
                'notes' => $cdrResponse->getNotes()
            ];
        }

        // dd($res);

    }

    public function uploadFile($file_content, $file_type)
    {
        $this->uploadStorage($this->document->filename, $file_content, $file_type);
    }


    public function updateState($state_type_id)
    {
        $this->document->update([
            'state_type_id' => $state_type_id
        ]);
    }

    //fin agregado


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