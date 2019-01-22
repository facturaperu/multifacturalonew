<?php

namespace App\CoreFacturalo\Inputs\Summaries;

use App\CoreFacturalo\Inputs\Common\ActionInput;
use App\CoreFacturalo\Inputs\InputFunctions;
use App\Models\Tenant\Company;
use App\Models\Tenant\Summary;
use Illuminate\Support\Str;

class SummaryInput
{
    public static function set($inputs, $service)
    {
        $company = Company::active();
        $soap_type_id = $company->soap_type_id;

        $date_of_reference = $inputs['date_of_reference'];
        $date_of_issue = date('Y-m-d');
        $summary_status_type_id = $inputs['summary_status_type_id'];

        $identifier = InputFunctions::identifier($soap_type_id, $date_of_issue, Summary::class);
        $filename = $company->number.'-'.$identifier;

        if ($summary_status_type_id === '1') {
            $documents = InputFunctions::findDocumentsBySummary($soap_type_id, $date_of_reference);
        } else {
            $documents = $inputs['documents'];
            $documents = InputFunctions::verifyDocumentsByVoided($soap_type_id, $date_of_reference, $documents, Summary::class);
        }

        return [
            'type' => 'summary',
            'user_id' => auth()->id(),
            'external_id' => Str::uuid(),
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
            'summary_status_type_id' => $summary_status_type_id,
            'ubl_version' => '2.0',
            'date_of_issue' => $date_of_issue,
            'date_of_reference' => $date_of_reference,
            'identifier' => $identifier,
            'filename' => $filename,
            'documents' => $documents,
//            'actions' => ActionInput::set($inputs),
        ];
    }
}