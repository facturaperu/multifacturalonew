<?php

namespace App\CoreFacturalo\Documents;

class RetentionBuilder extends DocumentBuilder
{
    public function save($inputs)
    {
        $document = $this->saveDocument($inputs['document']);
        $document->invoice()->create($inputs['document_base']);

        return $document;
    }
}