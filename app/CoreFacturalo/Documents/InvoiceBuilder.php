<?php

namespace App\CoreFacturalo\Documents;

use App\Models\Tenant\Document;

class InvoiceBuilder extends DocumentBuilder
{
    public function save($inputs)
    {
        $document = $this->saveDocument(array_except($inputs, 'invoice'));
        $document->invoice()->create($inputs['invoice']);

        return Document::find($document->id);
    }
}