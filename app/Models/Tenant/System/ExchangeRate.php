<?php

namespace App\Models\Tenant\System;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'date',
        'buy',
        'sell',
    ];
}