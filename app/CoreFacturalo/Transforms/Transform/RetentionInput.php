<?php

namespace App\CoreFacturalo\Transform;

use App\CoreFacturalo\Transforms\TransformApi\Retentions\RetentionInput as RetentionApiInput;
use App\CoreFacturalo\Transforms\TransformWeb\Retentions\RetentionInput as RetentionWebInput;

class RetentionInput
{
    public static function transform($inputs, $service)
    {
        if($service === 'api') {
            return RetentionApiInput::transform($inputs);
        }
        return RetentionWebInput::transform($inputs);
    }
}