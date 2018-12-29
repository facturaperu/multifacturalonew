<?php

namespace App\Models\Tenant\Catalogs;

class ChargeDiscountType extends ModelCatalog
{
    public $incrementing = false;
    public $timestamps = false;

    public function scopeWhereType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeWhereLevel($query, $level)
    {
        return $query->where('level', $level);
    }
}