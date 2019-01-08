<?php

namespace App\CoreFacturalo\Documents;

use App\Models\Tenant\Retention;

class RetentionBuilder
{
    public function save($inputs)
    {
        $data = $inputs['retention'];
        $retention = Retention::create($data);
        foreach ($data['documents'] as $row) {
            $retention->documents()->create($row);
        }

        return $retention;
    }
}