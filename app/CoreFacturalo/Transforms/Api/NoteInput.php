<?php

namespace App\CoreFacturalo\Transforms\Api;

use App\Models\Tenant\Document;
use Exception;

class NoteInput
{
    public static function transform($inputs, $document, $isWeb)
    {
        if($isWeb) {
            $aff_document_id = $inputs['affected_document_id'];
            $note_credit_or_debit_type_id = $inputs['note_credit_or_debit_type_id'];
            $note_description = $inputs['note_description'];

            $aux_aff_document = Document::find($aff_document_id);
        } else {
            $aff_document = $inputs['documento_afectado'];
            $aff_document_type_id = $aff_document['codigo_tipo_documento'];
            $aff_document_series = $aff_document['serie_documento'];
            $aff_document_number = $aff_document['numero_documento'];
            $note_credit_or_debit_type_id = $inputs['codigo_tipo_nota'];
            $note_description = $inputs['motivo_o_sustento_de_nota'];

            $aux_aff_document = self::findAffectedDocument($document['soap_type_id'], $aff_document_type_id, $aff_document_series, $aff_document_number);
        }

        if ($document['document_type_id'] === '07') {
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

        return [
            'type' => $type,
            'group_id' => $aux_aff_document->group_id,
            'document_base' => [
                'note_type' => $note_type,
                'note_credit_type_id' => $note_credit_type_id,
                'note_debit_type_id' => $note_debit_type_id,
                'note_description' => $note_description,
                'affected_document_id' => $aux_aff_document->id,
            ]
        ];
    }

    private static function findAffectedDocument($soap_type_id, $document_type_id, $series, $number)
    {
        $doc = Document::where('soap_type_id', $soap_type_id)
            ->where('document_type_id', $document_type_id)
            ->where('series', $series)
            ->where('number', $number)
            ->first();
        if(!$doc) {
            throw new Exception("El documento: {$series}-{$number} no se encuentra registrado.");
        }

        return $doc;
    }
}