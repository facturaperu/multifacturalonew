<?php

namespace App\CoreFacturalo\Formats\Common;

use App\CoreFacturalo\Helpers\Number\NumberLetter;

class LegendFormat
{
    public static function format($inputs)
    {
        $legends = array_key_exists('legends', $inputs)?$inputs['legends']:[];

        $legends[] = [
            'code' => 1000,
            'value' => NumberLetter::convertToLetter($inputs['total'])
        ];

        return $legends;
    }
}