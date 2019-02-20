<?php

namespace App\CoreFacturalo\Services\Extras;

use Carbon\Carbon;
use GuzzleHttp\Client;
use DiDom\Document as DiDom;

class ExchangeRate
{
    const URL_CONSULT = 'http://www.sunat.gob.pe/cl-at-ittipcam/tcS01Alias';

    protected $client;

    public function __construct()
    {

    }

    private function search($month, $year)
    {
        $client = new  Client(['base_uri' => 'http://www.sunat.gob.pe/cl-at-ittipcam/']);
        $response = $client->request('GET', "tcS01Alias?mes={$month}&anho={$year}");
        if ($response->getStatusCode() == 200 && $response != "") {
            $html = $response->getBody()->getContents();
            $xp = new DiDom($html);
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
        }

        return false;
    }

    public function searchDate($date)
    {
        $date = Carbon::parse($date);
        do {
            $res = $this->searchByDay($date);
            $date = $date->addDay(-1);
        } while (!$res);

        return $res;
    }

    private function searchByDay($date)
    {
        $day = $date->day;
        $year = $date->year;
        $month = $date->month;
        $exchange_rate = new  ExchangeRate();
        $exchange_rates = $exchange_rate->search($month, $year);
        if($exchange_rates) {
            foreach ($exchange_rates as $row)
            {
                $new_row = array_values($row);
                if ($new_row[0] == (int)$day) {
                    return [
                        'date_data' => $date->format('Y-m-d'),
                        'data' => [
                            'purchase' => $new_row[1],
                            'sale' => $new_row[2]
                        ]
                    ];
                }
            }
        }

        return false;
    }
}
