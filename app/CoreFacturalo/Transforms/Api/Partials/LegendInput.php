<?php

namespace App\CoreFacturalo\Transforms\Api\Partials;

class LegendInput
{
    public static function transform($inputs, $isWeb)
    {
        if($isWeb) {
            $legends = array_key_exists('legends', $inputs)?$inputs['legends']:null;
        } else {
            $legends = array_key_exists('leyendas', $inputs)?$inputs['leyendas']:null;
        }

        if(is_null($legends)) {
            return null;
        }

        $transform_legends = [];
        foreach ($legends as $row)
        {
            if($isWeb) {
                $code = $row['code'];
                $value = $row['value'];
            } else {
                $code = $row['codigo'];
                $value = $row['valor'];
            }

            $transform_legends[] = [
                'code' => $code,
                'value' => $value,
            ];
        }

        return $transform_legends;
    }
}