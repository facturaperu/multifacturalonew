<?php

namespace App\CoreFacturalo\Formats\Common;

class ActionFormat
{
    public static function format($inputs)
    {
        $actions = key_exists('actions', $inputs)?$inputs['actions']:[];

        return [
            'send_email' => array_key_exists('send_email', $actions)?(bool)$actions['send_email']:false,
            'send_xml_signed' => array_key_exists('send_xml_signed', $actions)?(bool)$actions['send_xml_signed']:true,
            'format_pdf' => array_key_exists('format_pdf', $actions)?$actions['format_pdf']:'a4',
        ];
    }
}