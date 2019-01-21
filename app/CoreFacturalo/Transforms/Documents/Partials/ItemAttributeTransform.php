<?php

namespace App\CoreFacturalo\Transforms\Documents\Partials;

use App\CoreFacturalo\Transforms\Functions;

class ItemAttributeTransform
{
    public static function transform($inputs)
    {
        if(key_exists('datos_adicionales', $inputs)) {
            $attributes = [];
            foreach ($inputs['datos_adicionales'] as $row)
            {
                $attributes[] = [
                    'code' => $row['codigo'],
                    'name' => $row['nombre'],
                    'value' => Functions::valueKeyInArray($row, 'valor'),
                    'start_date' => Functions::valueKeyInArray($row, 'fecha_inicio'),
                    'end_date' => Functions::valueKeyInArray($row, 'fecha_fin'),
                    'duration' => Functions::valueKeyInArray($row, 'duracion'),
                ];
            }

            return $attributes;
        }
        return null;
    }
}