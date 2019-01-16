<?php

namespace App\CoreFacturalo\Documents;

use App\Models\Tenant\Dispatch;

class DispatchBuilder
{
    public function save($inputs)
    {
        $dispatch = Dispatch::create($inputs);
        foreach ($inputs['items'] as $row) {
            $dispatch->details()->create($row);
        }

        return $dispatch;
    }
}