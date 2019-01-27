<?php

namespace App\Traits;

use App\Models\Tenant\Catalogs\DocumentType;

/**
 * 
 */
trait ReportTrait
{
    /**
     * Get type doc
     * @param  string $documentType
     * @return int
     */
    public function getTypeDoc($documentType) {
        foreach (DocumentType::all() as $item) {
            if (mb_strtoupper($item->description) == $documentType) return $item->id;
        }
        
        return null;
    }
}
