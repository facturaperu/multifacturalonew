<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class CurrencyType extends Model
{
    use UsesTenantConnection;

    public $timestamps = false;
    public $incrementing = false;
	protected $casts = ['id' => 'string'];
	
    protected $fillable = [
        'id', 'description', 'active', 'symbol'
    ];
}