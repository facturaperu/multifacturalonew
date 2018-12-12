<?php

namespace App\CoreBuilder\Interfaces;

/**
 * Interface DocumentInterface.
 */
interface DocumentInterface
{
    /**
     * Get Name for Document.
     *
     * @return string
     */
    public function getName();

    /**
     * Get Company
     *
     * @return object
     */
    public function getCompany();
}
