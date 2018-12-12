<?php

namespace App\CoreBuilder\Interfaces;

/**
 * Interface BuilderInterface.
 */
interface BuilderInterface
{
    /**
     * Create file for document.
     *
     * @param DocumentInterface $document
     *
     * @return string Content File
     */
    public function build(DocumentInterface $document);
}
