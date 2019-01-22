<?php

namespace App\CoreFacturalo\Inputs\Documents\Partials;

use App\Models\Tenant\Company;
use App\Models\Tenant\Document;

class NoteInput
{
    public static function set($inputs)
    {
        if(array_key_exists('note', $inputs)) {
            $document_type_id = $inputs['document_type_id'];
            $note = $inputs['note'];
            $note_credit_or_debit_type_id = $note['note_credit_or_debit_type_id'];
            $note_description = $note['note_description'];
            $affected_document = self::findAffectedDocument($note['document_type_id'], $note['series'], $note['number']);

            $type = ($document_type_id === '07')?'credit':'debit';

            return [
                'type' => $type,
                'group_id' => $affected_document->group_id,
                'note' => [
                    'note_type' => $type,
                    'note_credit_type_id' => ($type === 'credit')?$note_credit_or_debit_type_id:null,
                    'note_debit_type_id' => ($type === 'debit')?$note_credit_or_debit_type_id:null,
                    'note_description' => $note_description,
                    'affected_document_id' => $affected_document->id
                ]
            ];
        }
        return null;
    }

    private static function findAffectedDocument($document_type_id, $series, $number)
    {
        $company = Company::active();
        $soap_type_id = $company->soap_type_id;

        return Document::where('document_type_id', $document_type_id)
                        ->where('series', $series)
                        ->where('number', $number)
                        ->where('soap_type_id', $soap_type_id)
                        ->first();
    }
}