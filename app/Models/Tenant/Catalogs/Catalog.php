<?php

namespace App\Models\Tenant\Catalogs;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    use UsesTenantConnection;

    public $incrementing = false;
    public $timestamps = false;

    public function codes()
    {
        return $this->hasMany(Code::class);
    }
}