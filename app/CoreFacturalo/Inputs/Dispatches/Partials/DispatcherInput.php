<?php

namespace App\CoreFacturalo\Inputs\Dispatches\Partials;

class DispatcherInput
{
    public static function set($inputs)
    {
        if(key_exists('dispatcher', $inputs)) {
            $dispatcher = $inputs['dispatcher'];
            $identity_document_type_id = $dispatcher['identity_document_type_id'];
            $number = $dispatcher['number'];
            $name = $dispatcher['name'];

            return  [
                'identity_document_type_id' => $identity_document_type_id,
                'number' => $number,
                'name' => $name,
            ];
        }
        return null;
    }
}