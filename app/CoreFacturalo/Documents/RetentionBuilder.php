<?php

namespace App\CoreFacturalo\Documents;

use App\Models\Tenant\Retention;

class RetentionBuilder
{
    public function save($inputs)
    {
        $retention = Retention::create($inputs);
        foreach ($inputs['documents'] as $row) {
            $retention->documents()->create($row);
        }

        return $retention;
    }
}