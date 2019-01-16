<?php

namespace App\CoreFacturalo\Transforms\TransWeb\Documents\Partials;

class LegendInput
{
    public static function transform($inputs)
    {
        $legends = array_key_exists('legends', $inputs)?$inputs['legends']:null;

        if(is_null($legends)) {
            return null;
        }

        $transform_legends = [];
        foreach ($legends as $row)
        {
            $code = $row['code'];
            $value = $row['value'];

            $transform_legends[] = [
                'code' => $code,
                'value' => $value,
            ];
        }

        return $transform_legends;
    }
}