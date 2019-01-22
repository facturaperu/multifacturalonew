<?php

namespace App\CoreFacturalo\Transforms;

class TransformFunctions
{
    public static function valueKeyInArray($inputs, $key, $default = null)
    {
        return array_key_exists($key, $inputs)?$inputs[$key]:$default;
    }
}