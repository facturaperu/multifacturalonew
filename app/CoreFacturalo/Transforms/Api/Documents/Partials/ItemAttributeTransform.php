<?php

namespace App\CoreFacturalo\Transforms\Api\Documents\Partials;

use App\CoreFacturalo\Transforms\Api\FunctionsTransform;

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
                    'value' => FunctionsTransform::valueKeyInArray($row, 'valor'),
                    'start_date' => FunctionsTransform::valueKeyInArray($row, 'fecha_inicio'),
                    'end_date' => FunctionsTransform::valueKeyInArray($row, 'fecha_fin'),
                    'duration' => FunctionsTransform::valueKeyInArray($row, 'duracion'),
                ];
            }

            return $attributes;
        }
        return null;
    }
}