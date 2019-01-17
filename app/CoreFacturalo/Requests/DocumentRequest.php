<?php

namespace App\CoreFacturalo\Requests;

use App\CoreFacturalo\Requests\Partials\EstablishmentRequest;
use App\CoreFacturalo\Requests\Partials\PersonRequest;
use App\CoreFacturalo\Transforms\Functions;
use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use Illuminate\Support\Str;

class DocumentRequest
{
    public static function getInputs($inputs)
    {
        $document_type_id = $inputs['document_type_id'];
        $series = $inputs['series'];
        $number = $inputs['number'];

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;
        $number = Functions::newNumber($soap_type_id, $document_type_id, $series, $number, Document::class);
        $filename = Functions::filename($company, $document_type_id, $series, $number);

        Functions::validateUniqueDocument($soap_type_id, $document_type_id, $series, $number, Document::class);

        $invoice = null;
        $note = null;
        $type = 'invoice';
        $group_id = null;

        if(in_array($document_type_id, ['01', '03'])) {
            $group_id = ($document_type_id === '01')?'01':'02';
            $invoice = [
                'date_of_due' => $inputs['date_of_due'],
                'operation_type_id' => $inputs['operation_type_id']
            ];
        } else {
            $affected_document_id = $inputs['affected_document_id'];
            $affected_document_type_id = $inputs['affected_document_type_id'];
            $affected_series = $inputs['affected_series'];
            $affected_number = $inputs['affected_number'];
            $group_id = $inputs['affected_group_id'];
            $note_credit_or_debit_type_id = $inputs['note_credit_or_debit_type_id'];
            $note_description = $inputs['note_description'];

            if ($document_type_id === '07') {
                $note_type = 'credit';
                $note_credit_type_id = $note_credit_or_debit_type_id;
                $note_debit_type_id = null;
                $type = 'credit';
            } else {
                $note_type = 'debit';
                $note_credit_type_id = null;
                $note_debit_type_id = $note_credit_or_debit_type_id;
                $type = 'debit';
            }
            $note = [
                'note_type' => $note_type,
                'note_credit_type_id' => $note_credit_type_id,
                'note_debit_type_id' => $note_debit_type_id,
                'note_description' => $note_description,
                'affected_document_id' => $affected_document_id,
                'affected_document_type_id' => $affected_document_type_id,
                'affected_series' => $affected_series,
                'affected_number' => $affected_number
            ];
        }

        $inputs['type'] = $type;
        $inputs['number'] = $number;
        $inputs['group_id'] = $group_id;
        $inputs['filename'] = $filename;
        $inputs['soap_type_id'] = $soap_type_id;
        $inputs['ubl_version'] = '2.1';
        $inputs['state_type_id'] = '01';
        $inputs['user_id'] = auth()->id();
        $inputs['external_id'] = Str::uuid()->toString();
        $inputs['establishment'] = EstablishmentRequest::getData($inputs['establishment_id']);
        $inputs['customer'] = PersonRequest::getData($inputs['customer_id']);
        $inputs['invoice'] = $invoice;
        $inputs['note'] = $note;

        return $inputs;
    }
}