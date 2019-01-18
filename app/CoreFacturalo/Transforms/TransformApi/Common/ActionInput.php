<?php

namespace App\CoreFacturalo\Transforms\TransformApi\Common;

class ActionInput
{
    public static function transform($inputs)
    {
        if(key_exists('acciones', $inputs)) {
            $actions = $inputs['acciones'];
            $send_email = array_key_exists('enviar_email', $actions)?(bool)$actions['enviar_email']:false;
            $send_xml_signed = array_key_exists('enviar_xml_firmado', $actions)?(bool)$actions['enviar_xml_firmado']:true;
            $format_pdf = array_key_exists('formato_pdf', $actions)?$actions['formato_pdf']:'a4';

            return [
                'send_email' => $send_email,
                'send_xml_signed' => $send_xml_signed,
                'format_pdf' => $format_pdf
            ];
        }

        return [
            'send_email' => false,
            'send_xml_signed' => true,
            'format_pdf' => 'a4'
        ];
    }
}