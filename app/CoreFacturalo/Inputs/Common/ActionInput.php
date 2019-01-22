<?php

namespace App\CoreFacturalo\Inputs\Common;

use App\CoreFacturalo\Inputs\InputFunctions;

class ActionInput
{
    public static function set($inputs)
    {
        $actions = [];
        if(array_key_exists('actions', $inputs)) {
           if($inputs['actions']) {
               $actions = $inputs['actions'];
           }
        }

        $send_xml_signed = InputFunctions::valueKeyInArray($actions, 'send_xml_signed', true);
        if($inputs['group_id'] === '02') {
            $send_xml_signed = false;
        }

        return [
            'send_email' => InputFunctions::valueKeyInArray($actions, 'send_email', false),
            'send_xml_signed' => $send_xml_signed,
            'format_pdf' => InputFunctions::valueKeyInArray($actions, 'format_pdf', 'a4'),
        ];
    }
}