<?php

namespace App\CoreFacturalo\Documents;

class NoteBuilder extends DocumentBuilder
{
    public function save($inputs)
    {
        $document = $this->saveDocument(array_except($inputs, 'note'));
        $document->note()->create($inputs['note']);

        return $document;
    }
}