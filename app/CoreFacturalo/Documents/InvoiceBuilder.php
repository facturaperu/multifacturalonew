<?php

namespace App\CoreFacturalo\Documents;

class InvoiceBuilder extends DocumentBuilder
{
    public function save($inputs)
    {
        $document = $this->saveDocument(array_except($inputs, 'invoice'));
        $document->invoice()->create($inputs['invoice']);

        return $document;
    }
}