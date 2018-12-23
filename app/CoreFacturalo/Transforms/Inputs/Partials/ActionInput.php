<?php

namespace App\CoreFacturalo\Transforms\Inputs\Partials;

class ActionInput
{
    public static function transform($inputs)
    {
        if(key_exists('acciones', $inputs)) {
            $actions = $inputs['acciones'];
            $send_email = array_key_exists('enviar_email', $actions)?(bool)$actions['enviar_email']:false;

            return [
                'send_email' => $send_email
            ];
        }
        return null;
    }
}