<?php

namespace App\CoreFacturalo\Transform;

use App\CoreFacturalo\Transforms\TransformApi\Dispatches\DispatchInput as DispatchApiInput;
use App\CoreFacturalo\Transforms\TransformWeb\Dispatches\DispatchInput as DispatchWebInput;

class DispatchInput
{
    public static function transform($inputs, $service)
    {
        if($service === 'api') {
            return DispatchApiInput::transform($inputs);
        }
        return DispatchWebInput::transform($inputs);
    }
}