<?php

namespace App\CoreFacturalo\Transforms\TransWeb\Documents\Partials;

class ItemAttributeInput
{
    public static function transform($inputs)
    {
        $attributes = array_key_exists('attributes', $inputs)?$inputs['attributes']:null;

        if(is_null($attributes)) {
            return null;
        }

        $transform_attributes = [];
        foreach ($attributes as $row)
        {
            $attribute_type_id = $row['attribute_type_id'];
            $description = $row['description'];
            $value = array_key_exists('value', $row)?$row['value']:null;
            $start_date = array_key_exists('start_date', $row)?$row['start_date']:null;
            $end_date = array_key_exists('end_date', $row)?$row['end_date']:null;
            $duration = array_key_exists('duration', $row)?$row['duration']:null;

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