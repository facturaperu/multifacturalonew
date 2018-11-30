<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'description',
    ];
}