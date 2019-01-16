<?php

namespace App\CoreFacturalo\Documents;

use App\Models\Tenant\Summary;

class SummaryBuilder
{
    public function save($inputs)
    {
        $data = array_key_exists('summary', $inputs)?$inputs['summary']:$inputs;
        $summary = Summary::create($data);

        foreach ($data['documents'] as $row) {
            $summary->documents()->create($row);
        }
        return $summary;
    }
}