<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class Code extends ModelTenant
{
    use UsesTenantConnection;

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'catalog_id',
        'code',
        'description',
        'short',
        'symbol',
        'exportation',
        'free',
        'percentage',
        'base',
        'type',
        'level',
        'active',
    ];

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

    public function scopeWhereType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeWhereLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeOrderByDescription($query)
    {
        return $query->orderBy('description');
    }
}