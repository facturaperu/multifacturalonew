<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use UsesTenantConnection;

    public $timestamps = false;
}