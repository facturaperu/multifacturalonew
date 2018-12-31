<?php

namespace App\CoreFacturalo\Transforms\Inputs\Partials;

class ItemAttributeInput
{
    public static function transform($inputs, $isWeb)
    {
        if($isWeb) {
            $attributes = array_key_exists('attributes', $inputs)?$inputs['attributes']:null;
        } else {
            $attributes = array_key_exists('datos_adicionales', $inputs)?$inputs['datos_adicionales']:null;
        }

        if(is_null($attributes)) {
            return null;
        }

        $transform_attributes = [];
        foreach ($attributes as $row)
        {
            if($isWeb) {
                $code = $row['code'];
                $name = $row['name'];
                $value = array_key_exists('value', $row)?$row['value']:null;
                $start_date = array_key_exists('start_date', $row)?$row['start_date']:null;
                $end_date = array_key_exists('end_date', $row)?$row['end_date']:null;
                $duration = array_key_exists('duration', $row)?$row['duration']:null;
            } else {
                $code = $row['codigo'];
                $name = $row['nombre'];
                $value = array_key_exists('valor', $row)?$row['valor']:null;
                $start_date = array_key_exists('fecha_inicio', $row)?$row['fecha_inicio']:null;
                $end_date = array_key_exists('fecha_fin', $row)?$row['fecha_fin']:null;
                $duration = array_key_exists('duracion', $row)?$row['duracion']:null;
            }

            $transform_attributes[] = [
                'code' => $code,
                'name' => $name,
                'value' => $value,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'duration' => $duration,
            ];
        }

        return $transform_attributes;
    }
}