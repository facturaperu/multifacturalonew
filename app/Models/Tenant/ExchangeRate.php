<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'date',
        'buy',
        'sell',
        'date_original',
    ];
}