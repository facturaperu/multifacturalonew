<?php

namespace App\Models\Tenant\Catalogs;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class ChargeDiscountType extends ModelCatalog
{
    use UsesTenantConnection;

    protected $table = "cat_charge_discount_types";
}