<?php

namespace App\Core\Xml\Builder;

use App\Models\Tenant\Company;
use App\Models\Tenant\Document;

class InvoiceBuilder extends TwigBuilder
{
    public function build(Company $company, Document $document)
    {
        $template = 'invoice2.1.xml.twig';

        return $this->render($template, $company, $document);
    }
}