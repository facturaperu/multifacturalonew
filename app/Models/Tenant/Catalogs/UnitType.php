<?php

namespace App\Models\Tenant\Catalogs;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class UnitType extends ModelCatalog
{
    use UsesTenantConnection;
    
    protected $table = "cat_unit_types";
}