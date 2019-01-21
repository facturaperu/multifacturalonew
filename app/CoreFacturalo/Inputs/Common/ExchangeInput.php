<?php

namespace App\CoreFacturalo\Inputs\Common;

class ExchangeInput
{
    public static function set($inputs)
    {
        if(key_exists('exchange_rate', $inputs)) {
            $exchange_rate = $inputs['exchange_rate'];
            $currency_type_id_source = $exchange_rate['currency_type_id_source'];
            $currency_type_id_target = $exchange_rate['currency_type_id_target'];
            $factor = $exchange_rate['factor'];
            $date_of_exchange_rate = $exchange_rate['date_of_exchange_rate'];

            return [
                'currency_type_id_source' => $currency_type_id_source,
                'currency_type_id_target' => $currency_type_id_target,
                'factor' => $factor,
                'date_of_exchange_rate' => $date_of_exchange_rate,
            ];
        }
        return null;
    }
}