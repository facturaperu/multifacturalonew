<?php

namespace App\CoreFacturalo\Transform;

use App\CoreFacturalo\Transforms\TransformApi\Documents\SummaryInput as SummaryApiInput;
use App\CoreFacturalo\Transforms\TransformWeb\Documents\SummaryInput as SummaryWebInput;

class SummaryInput
{
    public static function transform($inputs, $service)
    {
        if($service === 'api') {
            return SummaryApiInput::transform($inputs);
        }
        return SummaryWebInput::transform($inputs);
    }
}