<?php

namespace App\CoreFacturalo\Inputs\Common;

class ActionInput
{
    public static function set($inputs)
    {
        $actions = key_exists('actions', $inputs)?$inputs['actions']:[];

        $send_email = array_key_exists('send_email', $actions)?(bool)$actions['send_email']:false;
        $send_xml_signed = array_key_exists('send_xml_signed', $actions)?(bool)$actions['send_xml_signed']:true;
        $format_pdf = array_key_exists('format_pdf', $actions)?$actions['format_pdf']:'a4';

        return [
            'send_email' => $send_email,
            'send_xml_signed' => $send_xml_signed,
            'format_pdf' => $format_pdf,
        ];
    }
}