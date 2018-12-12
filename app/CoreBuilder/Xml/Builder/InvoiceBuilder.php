<?php

namespace App\CoreBuilder\Xml\Builder;

use App\CoreBuilder\Interfaces\BuilderInterface;
use App\CoreBuilder\Interfaces\DocumentInterface;

class InvoiceBuilder extends TwigBuilder implements BuilderInterface
{
    public function build(DocumentInterface $document)
    {
        $template = 'invoice2.1.xml.twig';

        return $this->render($template, $document);
    }
}