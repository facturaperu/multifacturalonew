<?php

namespace App\Core\Helpers;

use App\Models\Tenant\Error;

class ErrorHelper
{
    public static function getErrorFromFault(\SoapFault $fault)
    {
        $code = $fault->faultcode;
        $code = preg_replace('/[^0-9]+/', '', $code);
        $msg = '';

        if (empty($code)) {
            $code = preg_replace('/[^0-9]+/', '', $fault->faultstring);
        }

        if ($code) {
            $msg = static::getMessageError($code);
        }

        if (empty($msg)) {
            $msg = isset($fault->detail) ? $fault->detail->message : $fault->faultstring;
        }

        $err['code'] = $code;
        $err['message'] = $msg;

        return $err;
    }

    public static function getMessageError($code)
    {
        $error = Error::find($code);
        return ($error)?$error->description:'';
    }
}