<?php

namespace App\CoreBuilder\WS\Reader;

use App\CoreBuilder\WS\Response\CdrResponse;

/**
 * Interface CdrReaderInterface.
 */
interface CdrReaderInterface
{
    /**
     * Get Cdr using DomDocument.
     *
     * @param string $xml
     *
     * @return CdrResponse
     */
    public function getCdrResponse($xml);
}
