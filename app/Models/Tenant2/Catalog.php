<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class Catalog extends ModelTenant
{
    use UsesTenantConnection;

    public function codes()
    {
        return $this->hasMany(Code::class);
    }
}