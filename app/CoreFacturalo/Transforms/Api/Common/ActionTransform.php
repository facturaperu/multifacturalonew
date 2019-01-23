<?php

namespace App\CoreFacturalo\Transforms\Api\Common;

use App\CoreFacturalo\Transforms\Api\FunctionsTransform;

class ActionTransform
{
    public static function transform($inputs)
    {
        if(key_exists('acciones', $inputs)) {
            $actions = $inputs['acciones'];
            return [
                'send_email' => FunctionsTransform::valueKeyInArray($actions, 'enviar_email'),
                'send_xml_signed' => FunctionsTransform::valueKeyInArray($actions, 'enviar_xml_firmado'),
                'format_pdf' => FunctionsTransform::valueKeyInArray($actions, 'formato_pdf')
            ];
        }
        return null;
    }
}