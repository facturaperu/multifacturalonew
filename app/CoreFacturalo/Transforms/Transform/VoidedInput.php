<?php

namespace App\CoreFacturalo\Transform;

use App\CoreFacturalo\Transforms\TransformApi\Documents\VoidedInput as VoidedApiInput;
use App\CoreFacturalo\Transforms\TransformWeb\Documents\VoidedInput as VoidedWebInput;

class VoidedInput
{
    public static function transform($inputs, $service)
    {
        if($service === 'api') {
            return VoidedApiInput::transform($inputs);
        }
        return VoideWebInput::transform($inputs);
    }
}