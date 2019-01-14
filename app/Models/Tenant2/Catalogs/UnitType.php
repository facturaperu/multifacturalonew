<?php

namespace App\Models\Tenant\Catalogs;

class UnitType extends ModelCatalog
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