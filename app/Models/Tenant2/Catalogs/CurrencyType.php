<?php

namespace App\Models\Tenant\Catalogs;

class CurrencyType extends ModelCatalog
{
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'description',
        'symbol',
        'active',
    ];
}