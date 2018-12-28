<?php

namespace App\Models\Tenant\Catalogs;

class ChargeDiscountType extends ModelCatalog
{
    public $incrementing = false;
    public $timestamps = false;

    public function scopeWhereCharges($query)
    {
        return $query->where('type', 'charge');
    }

    public function scopeWhereDiscounts($query)
    {
        return $query->where('type', 'discount');
    }
}