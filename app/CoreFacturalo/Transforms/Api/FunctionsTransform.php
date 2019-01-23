<?php

namespace App\CoreFacturalo\Transforms\Api;

use App\Models\Tenant\Series;
use Exception;

class FunctionsTransform
{
    public static function valueKeyInArray($inputs, $key, $default = null)
    {
        return array_key_exists($key, $inputs)?$inputs[$key]:$default;
    }

    public static function validateSeries($data)
    {
        $series = Series::where('number', $data['series'])
                        ->where('document_type_id', $data['document_type_id'])
                        ->where('establishment_id', $data['establishment_id'])
                        ->first();

        if(!$series) {
            throw new Exception("La serie ingresada {$data['series']}, es incorrecta.");
        }
    }
}