<?php

namespace App\Models\Tenant\Catalogs;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class CurrencyType extends ModelCatalog
{
    use UsesTenantConnection;

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id', 'description', 'active', 'symbol'
    ];

    protected $casts = ['id' => 'string'];
}