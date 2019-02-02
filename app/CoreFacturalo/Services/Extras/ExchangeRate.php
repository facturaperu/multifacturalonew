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

    public function search($month, $year)
    {
        $client = new  Client(['base_uri' => 'http://www.sunat.gob.pe/cl-at-ittipcam/']);
        $response = $client->request('GET', "tcS01Alias?mes={$month}&anho={$year}");
        if ($response->getStatusCode() == 200 && $response != "") {
            $html = $response->getBody()->getContents();
            $xp = new DiDom($html);
//            dd($xp);
            $sub_headings = $xp->find('form table');
            $trs = $sub_headings[1]->find('tr');
            $values = [];
          for($i = 1; $i < count($trs); $i++)
          {
              $tr = $trs[$i];
              $tds = $tr->find('td');

              foreach($tds as $td)
              {
                  $values[] = trim(preg_replace("/[\t|\n|\r]+/", '', $td->text()));
              }
          }

        return collect($values)->chunk(3)->toArray();
//        {
//            $tds = $sub_heading->find('td');
//            dd($tds[1]->text());
//        }

//            echo($html);
//            $xp = new DiDom($html);

        }

        return false;
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
