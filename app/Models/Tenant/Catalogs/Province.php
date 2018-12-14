<?php

namespace App\Models\Tenant\Catalogs;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class Province extends ModelCatalog
{
    use UsesTenantConnection;

    public $incrementing = false;
    public $timestamps = false;

    static function idByDescription($description)
    {
        $code = static::where('description', $description)->get();
        if (count($code) > 0) {
            return $code[0]->id;
        }
        return '1501';
    }
}