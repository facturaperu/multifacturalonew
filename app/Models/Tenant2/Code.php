<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class Code extends ModelTenant
{
    use UsesTenantConnection;

    public $incrementing = false;

    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }

    public function scopeWhereActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeWhereCatalog($query, $catalog_id)
    {
        return $query->where('catalog_id', $catalog_id);
    }

    public function scopeWhereCodes($query, $code_ids)
    {
        return $query->whereIn('code', $code_ids);
    }

    public function orderByDescription($query)
    {
        return $query->orderBy('description');
    }
}