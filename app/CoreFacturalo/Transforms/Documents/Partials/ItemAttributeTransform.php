<?php

namespace App\CoreFacturalo\Transforms\Documents\Partials;

use App\CoreFacturalo\Transforms\TransformFunctions;

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
                    'value' => TransformFunctions::valueKeyInArray($row, 'valor'),
                    'start_date' => TransformFunctions::valueKeyInArray($row, 'fecha_inicio'),
                    'end_date' => TransformFunctions::valueKeyInArray($row, 'fecha_fin'),
                    'duration' => TransformFunctions::valueKeyInArray($row, 'duracion'),
                ];
            }

            return $attributes;
        }
        return null;
    }
}