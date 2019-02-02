<?php
namespace App\Http\Controllers\Tenant\Api;

use App\CoreFacturalo\Services\Dni\Dni;
use App\CoreFacturalo\Services\Extras\ExchangeRate;
use App\CoreFacturalo\Services\Ruc\Sunat;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\Province;
use Carbon\Carbon;
use Dompdf\Exception;

class ServiceController extends Controller
{
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

//    public function validateCpe(Request $request)
//    {
//        $validateCpe = new ValidateCpe();
//        $documents = Document::whereIn('external_id', $request->input('documents'))->get();
//        $res = [];
//        foreach($documents as $document) {
//            $res[] = $validateCpe->search($document->document_type_code, $document->series, $document->number, $document->date_of_issue);
//        }
//        return $res;
//    }
//
//    public function consultStatus($documents)
//    {
//        $consultCdrService = new ConsultCdrService();
//        $res = $consultCdrService->getStatus('01', 'F001', 5);
//
//        return $res;
//    }
//
//    public function consultCdrStatus($documents)
//    {
//        $consultCdrService = new ConsultCdrService();
//        $res = $consultCdrService->getStatusCdr('01', 'F001', 4);
//
//        return $res;
//    }
//
//    public function exchange_rate(Request $request)
//    {
//        $records = [];
//        if (!$request['last_date']) {
//            $records = ExchangeRate::where('date', $request['date'])->get()->keyBy('date')->toArray();
//        }
//        if (empty($records)) {
//            $exchange_rate = new ExchangeRateService($request['cur_date'],$request['last_date']);
//            $records = $exchange_rate->get();
//            if ($records) {
//                foreach ($records as $key => $item) {
//                    if (!ExchangeRate::where('date',$key)->first() && (Carbon::today()->toDateString() != $key || $item['date'] == $item['date_original'])) {
//                        ExchangeRate::create($item);
//                    }
//                }
//            }
//        }
//        if ($records) {
//            return [
//                'success' => true,
//                'message' => 'Tipos de cambio obtenidos',
//                'data' => $records
//            ];
//        } else {
//            return [
//                'success' => false,
//                'message' => 'Error'
//            ];
//        }
//    }
//
//    public function searchExchangeRateByDate(Request $request)
//    {
//        $date = $request->input('exchange_rate_date');
//        $exchange_rate = ExchangeRate::where('date', $date)->first();
//        if($exchange_rate) {
//              return [
//                  'success' => true,
//                  'data' => $exchange_rate
//              ];
//        } else {
//            return [
//                'success' => false,
//                'message' => "Tipo de cambio no encontrado en la fecha {$date}"
//            ];
//        }
//    }

    public function exchangeRateTest()
    {
        $date_of_exchange_rate = Carbon::parse('2019-01-25');
        $exchange_rate = \App\Models\Tenant\ExchangeRate::where('date', $date_of_exchange_rate->format('Y-m-d'))->first();
        if(!$exchange_rate) {
            $year = $date_of_exchange_rate->year;
            $month = $date_of_exchange_rate->month;
            $exchange_rate = new  ExchangeRate();
            $exchange_rates = $exchange_rate->search($month, $year);
            if($exchange_rates) {
                dd($exchange_rates[2]);
                foreach ($exchange_rates as $row)
                {
                    dd($row);
//                    dd($row[0]);
//                    $date = Carbon::parse($year.'-'.$month.'-'.$row['0'])->format('Y-m-d');
//                    var_dump($year.'-'.$month.'-'.$row[0]);
//                    \App\Models\Tenant\ExchangeRate::firstOrCreate([
//                        'date' => $date
//                    ],[
//                        'buy' => $row[1],
//                        'sell' => $row[2],
//                        'date_original' => $date
//                    ]);
                    //dd('aca');
                }
            }
            //$exchange_rate = \App\Models\Tenant\ExchangeRate::where('date', $date_of_exchange_rate->format('Y-m-d'))->first();
        }

//        if(!$exchange_rate) {
//            $exchange_rate = \App\Models\Tenant\ExchangeRate::where('date', $date_of_exchange_rate->addDay(-1)->format('Y-m-d'))->first();
//        }

        dd($exchange_rate);

    }

    public function exchangeRate()
    {
        //$date_of_exchange_rate = '2018-12-08';//$request->input('date_of_exchange_rate');
        //$exchange_rate = new  ExchangeRate();
       // $response = $exchange_rate->search($date_of_exchange_rate);

       return [
            'success' => true,
            'data' => [
            'sell' => 1
            ]
       ];
    }
}