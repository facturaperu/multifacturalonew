<?php

namespace App\Models\Tenant\Catalogs;

use App\Models\Tenant\ModelTenant;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Catalog extends ModelTenant
{
    use UsesTenantConnection;

    public function codes()
    {
        return $this->hasMany(Code::class);
    }
}