<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class ProcessType extends Model
{
    use UsesTenantConnection;

    public $timestamps = false;
}