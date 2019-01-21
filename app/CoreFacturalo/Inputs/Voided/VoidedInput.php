<?php

namespace App\CoreFacturalo\Inputs\Voided;

use App\CoreFacturalo\Inputs\Common\ActionInput;
use App\CoreFacturalo\Inputs\Functions;
use App\Models\Tenant\Company;
use App\Models\Tenant\Voided;
use Illuminate\Support\Str;

class VoidedInput
{
    public static function set($inputs)
    {
        $company = Company::active();
        $soap_type_id = $company->soap_type_id;

        $date_of_reference = $inputs['date_of_reference'];
        $date_of_issue = date('Y-m-d');

        $documents = $inputs['documents'];
        $documents = Functions::verifyDocumentsByVoided($soap_type_id, $date_of_reference, $documents, Voided::class);

        $identifier = Functions::identifier($soap_type_id, $date_of_issue, Voided::class);
        $filename = $company->number.'-'.$identifier;

        return [
            'type' => 'voided',
            'user_id' => auth()->id(),
            'external_id' => Str::uuid(),
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
            'ubl_version' => '2.0',
            'date_of_issue' => $date_of_issue,
            'date_of_reference' => $date_of_reference,
            'identifier' => $identifier,
            'filename' => $filename,
            'documents' => $documents,
            'actions' => ActionInput::set($inputs),
        ];
    }
}