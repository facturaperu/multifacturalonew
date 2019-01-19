<?php

namespace App\CoreFacturalo\Transforms\TransformApi\Voided;

use App\CoreFacturalo\Transforms\TransformApi\Common\ActionInput;
use App\Models\Tenant\Company;
use Illuminate\Support\Str;

class VoidedInput
{
    public static function transform($inputs)
    {
        $company = Company::active();
        $soap_type_id = $company->soap_type_id;

        $date_of_reference = $inputs['fecha_de_emision_de_documentos'];
        $date_of_issue = date('Y-m-d');

        $docs = array_key_exists('documentos', $inputs)?$inputs['documentos']:[];
        $documents = self::verifyDocuments($soap_type_id, $date_of_reference, $docs);
        $identifier = self::identifier($soap_type_id, $date_of_issue);
        $filename = self::filename($identifier);

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
            'actions' => ActionInput::transform($inputs),
        ];
    }
}