<?php

namespace App\CoreBuilder\Xml\Builder;

use App\Models\Tenant\Company;
use App\Models\Tenant\Summary;

class SummaryBuilder extends TwigBuilder
{
    public function build(Company $company, Summary $document)
    {
        return $this->render('summary.xml.twig', $company, $document);
    }
}