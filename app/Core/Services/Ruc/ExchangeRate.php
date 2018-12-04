<?php

namespace App\Core\Services\Ruc;

use Goutte\Client;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Tenant\ExchangeRate as ExchangeRateModel;

/**
 * Class Ruc.
 */
class ExchangeRate
{
    /**
     * @var date
     */
    private $lastDate;
    /**
     * @var Crawler
     */
    private $crawler;
    /**
     * @var Client
     */
    private $client;
    /**
     * @var string
     */
    private $curMonth;
    /**
     * @var int
     */
    private $curYear;
    /**
     * @var Crawler
     */
    private $error;

    /**
     * ExchangeRate constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->lastDate = $this->getLastDateExchangeRateFromDB();
        $this->crawler = $this->getCrawler();
        $this->curMonth = Carbon::today('America/Lima')->month;
        $this->curYear = Carbon::today('America/Lima')->year;
    }

    private function getLastDateExchangeRateFromDB()
    {
        $lastExchangeRate = ExchangeRateModel::orderBy('date','desc')->first();
        if (!$lastExchangeRate) {
            return null;
        }
        return Carbon::parse($lastExchangeRate->date,'America/Lima');
    }

    private function getCrawler()
    {
        return $this->client->request('GET', 'https://e-consulta.sunat.gob.pe/cl-at-ittipcam/tcS01Alias');
    }

    public function get()
    {
        $exchangeRates = $this->getExchangeRates();
        $filterExchangeRates = $this->getFilterExchangeRates($exchangeRates);
        if (!$filterExchangeRates) {
            $this->error = 'No hay tipos de cambio para registrar';
            return false;
        }
        return $filterExchangeRates;
    }

    /**
     * Get Last error message.
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }


    private function changeExchangeRatesMonthAndYear() {
        $form = $this->crawler->filter('[name=selectForm]')->first()->form();
        $this->crawler = $this->client->submit($form, [
            'mes' => $this->curMonth,
            'anho' => $this->curYear
        ]);
    }
    private function getExchangeRates() {
        $exchangeRates = $this->getExchangeRateFromMonthAndYear();
        if (empty($exchangeRates) || $this->lastDate && Carbon::today('America/Lima')->month != $this->lastDate->month) {
            $this->curMonth = Carbon::today('America/Lima')->subMonthNoOverflow()->month;
            $this->curYear = Carbon::today('America/Lima')->subMonthNoOverflow()->year;
            $this->changeExchangeRatesMonthAndYear();
            $exchangeRates += $this->getExchangeRateFromMonthAndYear();
        }
        if ($this->lastDate && Carbon::today('America/Lima')->subMonthNoOverflow()->month != $this->lastDate->month) {
            $firstDateToInsert = $this->lastDate->toDateString();
            $lastDateToInsert = Carbon::today('America/Lima')->subMonthNoOverflow()->toDateString();
            $period = CarbonPeriod::create($firstDateToInsert, '1 month', $lastDateToInsert);
            foreach ($period as $date) {
                $this->curMonth = $date->month;
                $this->curYear = $date->year;
                $this->changeExchangeRatesMonthAndYear();
                $exchangeRates += $this->getExchangeRateFromMonthAndYear();
            }
        }
        ksort($exchangeRates);
        return $exchangeRates;
    }

    private function getExchangeRateFromMonthAndYear() {
        $this->crawler->filter('table')->each(function($node,$i) use (&$result) {
            if ($i == 1) {
                $node->filter('tr')->each(function ($tr, $j) use (&$result) {
                    if ($j > 0) {
                        $date = '';
                        $buy = '';
                        $sell = '';
                        $tr->filter('td')->each(function ($td, $k) use (&$result,&$date,&$buy,&$sell) {
                            if ($k%3==0) {
                                $date = $this->curYear.'-'.$this->curMonth.'-'.str_pad(trim($td->text()), 2, "0", STR_PAD_LEFT);
                            } elseif ($k%3==1) {
                                $buy = trim($td->text());
                            } else {
                                $sell = trim($td->text());
                            }
                            $result[$date] = [
                                'date' => $date,
                                'buy' => $buy,
                                'sell' => $sell,
                            ];
                            $k++;
                        });
                    }
                    $j++;
                });
            }
            $i++;
        });
        return $result;
    }

    private function getFilterExchangeRates($exchangeRates) {
        $filterExchangeRates = [];
        end($exchangeRates);
        $key = key($exchangeRates);
        if (!$this->lastDate) {
            $filterExchangeRates[$key] = $exchangeRates[$key];
        }
        elseif (Carbon::parse($key,'America/Lima')->diffInDays($this->lastDate)) {
            $firstDateToInsert = $this->lastDate->addDay()->toDateString();
            $lastDateToInsert = Carbon::parse($key,'America/Lima')->toDateString();
            $period = CarbonPeriod::create($firstDateToInsert, $lastDateToInsert);
            foreach ($period as $date) {
                if (array_key_exists($date->format('Y-m-d'), $exchangeRates)) {
                    $filterExchangeRates[$date->format('Y-m-d')] = $exchangeRates[$date->format('Y-m-d')];
                }
            }
        }
        return $filterExchangeRates;
    }
}
