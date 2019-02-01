<?php

namespace App\CoreFacturalo\Services\Extras;

use GuzzleHttp\Client;
use DiDom\Document as DiDom;

class ExchangeRate
{
    const URL_CONSULT = 'http://www.sunat.gob.pe/cl-at-ittipcam/tcS01Alias';

    protected $client;

    public function __construct()
    {
        //$this->client = new Client();
    }

    public function search($date_of_exchange_rate)
    {
        $client = new  Client(['base_uri' => 'http://www.sunat.gob.pe/cl-at-ittipcam/']);
        $response = $client->request('GET', 'tcS01Alias?mes=01&anho=2019');
        if ($response->getStatusCode() == 200 && $response != "") {
            $html = $response->getBody()->getContents();
            $xp = new DiDom($html);
            $sub_headings = $xp->find('.form-table tbody tr');
            dd($sub_headings);
//            echo($html);
//            $xp = new DiDom($html);

        }
//        $response = $this->client->request('GET', self::URL_CONSULT, [
//            'form_params' => [
////                'mesElegido' => '01',
////                'anioElegido' => '2018',
//                'mes' => 3,
//                'anho' => 2018,
//                //'accion' => 'init'
//            ]
//        ]);

//        $html = $response->getBody()->getContents();
//        $xp = new DiDom($html);
//        dd($xp);
//        $sub_headings = $xp->find('.rgMasterTable tbody tr');
//        foreach($sub_headings as $sub_heading)
//        {
//            $tds = $sub_heading->find('td');
//            dd($tds[1]->text());
//        }
    }
}
