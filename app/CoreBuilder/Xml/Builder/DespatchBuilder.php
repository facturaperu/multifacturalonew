<?php

namespace App\CoreBuilder\Xml\Builder;

use Greenter\Builder\BuilderInterface;
use Greenter\Model\DocumentInterface;

/**
 * Class DespatchBuilder
 * @package Greenter\Xml\Builder
 */
class DespatchBuilder extends TwigBuilder implements BuilderInterface
{
    /**
     * Create xml for document.
     *
     * @param DocumentInterface $document
     * @return string
     */
    public function build(DocumentInterface $document)
    {
        return $this->render('despatch.xml.twig', $document);
    }
}