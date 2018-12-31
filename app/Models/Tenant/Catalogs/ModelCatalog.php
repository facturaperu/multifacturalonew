<?php
namespace App\Models\Tenant\Catalogs;

use App\Models\Tenant\ModelTenant;

class ModelCatalog extends ModelTenant
{
    public function scopeWhereActive($query, $active = true)
    {
        return $query->where('active', $active);
    }

    public function scopeOrderByDescription($query)
    {
        return $query->orderBy('description');
    }

    public function scopeOrderById($query)
    {
        return $query->orderBy('id');
    }
}