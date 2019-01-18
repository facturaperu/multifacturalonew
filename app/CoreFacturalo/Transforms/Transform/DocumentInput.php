<?php

namespace App\CoreFacturalo\Transform;

use App\CoreFacturalo\Transforms\TransformApi\Documents\DocumentInput as DocumentApiInput;
use App\CoreFacturalo\Transforms\TransformWeb\Documents\DocumentInput as DocumentWebInput;

class DocumentInput
{
    public static function transform($inputs, $service)
    {
        if($service === 'api') {
            return DocumentApiInput::transform($inputs);
        }
        return DocumentWebInput::transform($inputs);
    }
}