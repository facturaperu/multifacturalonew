<?php

namespace App\CoreFacturalo\Transforms\Api\Common;

class LegendTransform
{
    public static function transform($inputs)
    {
        if(key_exists('leyendas', $inputs)) {
            $legends = [];
            foreach ($inputs['leyendas'] as $row)
            {
                $legends[] = [
                    'code' => $row['codigo'],
                    'value' => $row['valor'],
                ];
            }
            return $legends;
        }
        return null;
    }
}