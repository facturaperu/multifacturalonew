<?php

namespace App\CoreFacturalo\Inputs\Common;

use App\CoreFacturalo\Helpers\Number\NumberLetter;

class LegendInput
{
    public static function set($inputs)
    {
        $legends = key_exists('legends', $inputs)?$inputs['legends']:[];

        foreach ($legends as $row)
        {
            $code = $row['code'];
            $value = $row['value'];

            $legends[] = [
                'code' => $code,
                'value' => $value
            ];
        }

        $legends[] = [
            'code' => 1000,
            'value' => NumberLetter::convertToLetter($inputs['total'])
        ];

        return $legends;
    }
}