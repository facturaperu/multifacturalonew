<?php
namespace App\Models\Tenant\Catalogs;

use App\Models\Tenant\ModelTenant;

class ModelCatalog extends ModelTenant
{
    public static function listActivesAndOrderByDescription()
    {
        return static::where('active', true)
                        ->orderBy('description')
                        ->get();
    }
}