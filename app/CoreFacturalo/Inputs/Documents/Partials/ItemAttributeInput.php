<?php

namespace App\CoreFacturalo\Inputs\Documents\Partials;

class ItemAttributeInput
{
    public static function set($inputs)
    {
        if(array_key_exists('attributes', $inputs)) {
            if($inputs['attributes']) {
                $attributes = [];
                foreach ($inputs['attributes'] as $row) {
                    $code = $row['code'];
                    $name = $row['name'];
                    $value = array_key_exists('valor', $row) ? $row['value'] : null;
                    $start_date = array_key_exists('start_date', $row) ? $row['start_date'] : null;
                    $end_date = array_key_exists('start_date', $row) ? $row['start_date'] : null;
                    $duration = array_key_exists('duration', $row) ? $row['duration'] : null;

                    $attributes[] = [
                        'code' => $code,
                        'name' => $name,
                        'value' => $value,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'duration' => $duration,
                    ];
                }
                return $attributes;
            }
        }
        return null;
    }
}