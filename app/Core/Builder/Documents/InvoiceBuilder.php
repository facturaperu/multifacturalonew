<?php

namespace App\Core\Builder\Documents;

use App\Models\Tenant\Document;

class InvoiceBuilder extends DocumentBuilder
{
    public function save($inputs)
    {
        $document = array_key_exists('document', $inputs)?$inputs['document']:$inputs;
        $this->saveDocument($document);

        $document_base = array_key_exists('document_base', $inputs)?$inputs['document_base']:$inputs;
        $this->document->invoice()->create($document_base);
    }

    public function getDocument()
    {
        return Document::find($this->document->id);
    }

}