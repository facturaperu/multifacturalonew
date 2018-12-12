<?php

namespace App\Models\Tenant\System;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'description',
    ];
}