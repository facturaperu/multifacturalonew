<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class CurrencyType extends Model
{
    use UsesTenantConnection;

    public $timestamps = false;

    protected $fillable = [
        'code', 'description', 'active', 'symbol'
    ];
}