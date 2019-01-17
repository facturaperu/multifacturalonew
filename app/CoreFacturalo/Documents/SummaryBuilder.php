<?php

namespace App\CoreFacturalo\Documents;

use App\Models\Tenant\Summary;

class SummaryBuilder
{
    public function save($inputs)
    {
        $summary = Summary::create($inputs);
        foreach ($inputs['documents'] as $row) {
            $summary->summary_documents()->create($row);
        }
        return $summary;
    }
}