<?php

namespace App\Core\Xml\Builder;

use App\Models\Tenant\Company;
use App\Models\Tenant\Voided;

class VoidedBuilder extends TwigBuilder
{
    public function build(Company $company, Voided $document)
    {
        return $this->render('voided.xml.twig', $company, $document);
    }
}