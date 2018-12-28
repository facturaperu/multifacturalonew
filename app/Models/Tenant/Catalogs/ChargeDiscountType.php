<?php

namespace App\Models\Tenant\Catalogs;

class ChargeDiscountType extends ModelCatalog
{
    public $incrementing = false;
    public $timestamps = false;

    public function scopeWhereCharge($query)
    {
        return $query->where('type', 'charge');
    }

    public function scopeWhereDiscount($query)
    {
        return $query->where('type', 'discount');
    }
}