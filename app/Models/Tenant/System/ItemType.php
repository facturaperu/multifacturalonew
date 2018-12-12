<?php

namespace App\Models\Tenant\System;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    use UsesTenantConnection;

    public $incrementing = false;
    public $timestamps = false;
}