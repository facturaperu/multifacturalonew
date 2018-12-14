<?php

namespace App\Models\Tenant\Catalogs;

use Illuminate\Database\Eloquent\Model;

class ModelCatalog extends Model
{
    public function scopeActives($query)
    {
        return $query->where('active', true);
    }

    public function scopeOrderByDescription($query)
    {
        return $query->orderBy('description');
    }

    public static function listActivesAndOrderByDescription()
    {
        return self::actives()->orderByDescription()->get();
    }
}