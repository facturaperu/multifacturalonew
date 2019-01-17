<?php

namespace App\CoreFacturalo\Transforms\TransformApi\Common;

class ExchangeInput
{
    public static function transform($inputs)
    {
        $exchange_rate = array_key_exists('tipo_de_cambio', $inputs)?$inputs['tipo_de_cambio']:null;

        if(is_null($exchange_rate)) {
            return null;
        }

        $currency_type_id_source = $exchange_rate['codigo_tipo_moneda_referencia'];
        $currency_type_id_target = $exchange_rate['codigo_tipo_moneda_objetivo'];
        $factor = $exchange_rate['factor'];
        $date_of_exchange_rate = $exchange_rate['fecha_tipo_de_cambio'];

        return [
            'currency_type_id_source' => $currency_type_id_source,
            'currency_type_id_target' => $currency_type_id_target,
            'factor' => $factor,
            'date_of_exchange_rate' => $date_of_exchange_rate,
        ];
    }
}