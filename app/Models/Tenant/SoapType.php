<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class SoapType extends Model
{
    use UsesTenantConnection;

    public $incrementing = false;
    public $timestamps = false;
}