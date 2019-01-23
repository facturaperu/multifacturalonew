<?php

namespace App\CoreFacturalo\Requests\Web\Validation;

class VoidedValidation
{
    public static function validation($inputs)
    {
        $inputs['documents'] = Functions::voidedDocuments($inputs, 'voided');
        return $inputs;
    }
}