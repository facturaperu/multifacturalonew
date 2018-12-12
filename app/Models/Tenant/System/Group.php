<?php

namespace App\Models\Tenant\System;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use UsesTenantConnection;

    public $timestamps = false;
}