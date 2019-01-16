<?php

namespace App\CoreFacturalo\Transforms\TransWeb\Common;

class ActionInput
{
    public static function transform($inputs)
    {
        $actions = array_key_exists('actions', $inputs)?$inputs['actions']:[];

        $send_email = array_key_exists('send_email', $actions)?$actions['send_email']:false;
        $send_xml_signed = array_key_exists('send_xml_signed', $actions)?$actions['send_xml_signed']:true;
        $format_pdf = array_key_exists('format_pdf', $actions)?$actions['format_pdf']:'a4';

        return [
            'send_email' => $send_email,
            'send_xml_signed' => $send_xml_signed,
            'format_pdf' => $format_pdf
        ];
    }
}