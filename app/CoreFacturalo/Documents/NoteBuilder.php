<?php

namespace App\CoreFacturalo\Documents;

use App\Models\Tenant\Document;
use Exception;

class NoteBuilder extends DocumentBuilder
{
    public function save($inputs)
    {
        $document = $this->saveDocument($inputs['document']);
        $document->note()->create($inputs['document_base']);

//        $document = array_key_exists('document', $inputs)?$inputs['document']:$inputs;
//        $document = $this->updateData($document);
//
//        $note = array_key_exists('document_base', $inputs)?$inputs['document_base']:$inputs;
//        if(!$note['affected_document_id']) {
//            $affected_document = $this->findAffectedDocument($document, $note);
//            $note['affected_document_id'] = $affected_document->id;
//        } else {
//            $affected_document = Document::find($note['affected_document_id']);
//        }
//
//        $document['type'] = ($document['document_type_id'] === '07')?'credit':'debit';
//        $document['group_id'] = $affected_document['group_id'];
//
//        $doc = $this->saveDocument($document);
//        $doc->note()->create($note);

        return $document;
    }


}