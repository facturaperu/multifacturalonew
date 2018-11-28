<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use UsesTenantConnection;

    public $incrementing = false;
    public $timestamps = false;

    static function idByDescription($description)
    {
        $code = static::where('description', $description)->get();
        if (count($code) > 0) {
            return $code[0]->id;
        }
        return '15';
    }
}