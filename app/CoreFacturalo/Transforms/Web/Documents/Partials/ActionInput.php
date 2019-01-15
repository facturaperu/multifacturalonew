<?php

namespace App\CoreFacturalo\Transforms\Web\Documents\Partials;

class ActionInput
{
    public static function transform($inputs)
    {
        $actions = array_key_exists('actions', $inputs)?$inputs['actions']:null;

        if(is_null($actions)) {
            return null;
        }

        $send_email = $actions['send_email'];
        $send_xml_signed = $actions['send_xml_signed'];

        return [
            'send_email' => ($send_email)?(bool)$send_email:false,
            'send_xml_signed' =>  ($send_xml_signed)?(bool)$send_xml_signed:true
        ];
    }
}