<?php

namespace App\CoreFacturalo\Transforms\Common;

use App\CoreFacturalo\Transforms\Functions;

class ActionTransform
{
    public static function transform($inputs)
    {
        if(key_exists('acciones', $inputs)) {
            return [
                'send_email' => Functions::valueKeyInArray($inputs, 'enviar_email'),
                'send_xml_signed' => Functions::valueKeyInArray($inputs, 'enviar_xml_firmado'),
                'format_pdf' => Functions::valueKeyInArray($inputs, 'formato_pdf')
            ];
        }
        return null;
    }
}