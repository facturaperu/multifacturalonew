<?php

namespace App\CoreFacturalo\Transforms\TransApi\Documents\Partials;

class ItemAttributeInput
{
    public static function transform($inputs)
    {
        $attributes = array_key_exists('datos_adicionales', $inputs)?$inputs['datos_adicionales']:null;

        if(is_null($attributes)) {
            return null;
        }

        $transform_attributes = [];
        foreach ($attributes as $row)
        {
            $attribute_type_id = $row['codigo'];
            $description = $row['descripcion'];
            $value = array_key_exists('valor', $row)?$row['valor']:null;
            $start_date = array_key_exists('fecha_inicio', $row)?$row['fecha_inicio']:null;
            $end_date = array_key_exists('fecha_fin', $row)?$row['fecha_fin']:null;
            $duration = array_key_exists('duracion', $row)?$row['duracion']:null;

            $transform_attributes[] = [
                'attribute_type_id' => $attribute_type_id,
                'description' => $description,
                'value' => $value,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'duration' => $duration,
            ];
        }

        return $transform_attributes;
    }
}