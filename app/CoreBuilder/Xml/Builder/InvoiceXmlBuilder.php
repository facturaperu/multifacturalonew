<?php

namespace App\CoreBuilder\Xml\Builder;

use App\CoreBuilder\Interfaces\BuilderInterface;
use App\CoreBuilder\Interfaces\DocumentInterface;

class InvoiceXmlBuilder extends BladeBuilder implements BuilderInterface
{
    public function build(DocumentInterface $document)
    {
        $template = 'invoice';
        return $this->render($template, $document);
    }
}