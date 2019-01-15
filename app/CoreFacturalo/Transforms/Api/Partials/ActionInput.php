<?php

namespace App\CoreFacturalo\Transforms\Api\Partials;

class ActionInput
{
    public static function transform($inputs, $isWeb)
    {
        if($isWeb) {
            $actions = array_key_exists('actions', $inputs)?$inputs['actions']:null;
        } else {
            $actions = array_key_exists('acciones', $inputs)?$inputs['acciones']:null;
        }

        if(is_null($actions)) {
            return null;
        }

        if($isWeb) {
            $send_email = $actions['send_email'];
            $send_xml_signed = $actions['send_xml_signed'];
        } else {
            $send_email = array_key_exists('enviar_email', $actions)?(bool)$actions['enviar_email']:false;
            $send_xml_signed = array_key_exists('enviar_xml_firmado', $actions)?(bool)$actions['enviar_xml_firmado']:true;
        }

        return [
            'send_email' => ($send_email)?(bool)$send_email:false,
            'send_xml_signed' =>  ($send_xml_signed)?(bool)$send_xml_signed:true
        ];
    }
}