<?php

namespace App\CoreFacturalo\Transforms\Web\Documents\Partials;

class ExchangeInput
{
    public static function transform($inputs)
    {
        $exchange_rate = array_key_exists('exchange_rate', $inputs)?$inputs['exchange_rate']:null;

        if(is_null($exchange_rate)) {
            return null;
        }

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
}