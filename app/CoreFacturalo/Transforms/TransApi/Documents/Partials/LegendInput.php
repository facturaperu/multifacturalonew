<?php

namespace App\CoreFacturalo\Transforms\Api\Partials;

class LegendInput
{
    public static function transform($inputs)
    {
        $legends = array_key_exists('leyendas', $inputs)?$inputs['leyendas']:null;

        if(is_null($legends)) {
            return null;
        }

        $transform_legends = [];
        foreach ($legends as $row)
        {
            $code = $row['codigo'];
            $value = $row['valor'];

            $transform_legends[] = [
                'code' => $code,
                'value' => $value,
            ];
        }

        return $transform_legends;
    }
}