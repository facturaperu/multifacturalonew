<?php

namespace App\CoreBuilder\Zip;

/**
 * Interface DecompressInterface.
 */
interface DecompressInterface
{
    /**
     * Extract files.
     *
     * @param string        $content
     * @param callable|null $filter
     *
     * @return array
     */
    public function decompress($content, callable $filter = null);
}
