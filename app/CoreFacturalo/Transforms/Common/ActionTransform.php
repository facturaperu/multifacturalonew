<?php

namespace App\CoreFacturalo\Transforms\Common;

use App\CoreFacturalo\Transforms\TransformFunctions;

class ActionTransform
{
    public static function transform($inputs)
    {
        if(key_exists('acciones', $inputs)) {
            $actions = $inputs['acciones'];
            return [
                'send_email' => TransformFunctions::valueKeyInArray($actions, 'enviar_email'),
                'send_xml_signed' => TransformFunctions::valueKeyInArray($actions, 'enviar_xml_firmado'),
                'format_pdf' => TransformFunctions::valueKeyInArray($actions, 'formato_pdf')
            ];
        }
        return null;
    }
}