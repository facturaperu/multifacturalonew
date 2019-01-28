<?php

namespace App\Models\System;

use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;
//use Hyn\Tenancy\Models\Hostname;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use UsesSystemConnection;

    protected $fillable = [
        'hostname_id',
        'number',
        'name',
        'email',
        'token',
        'locked'
    ];

    public function hostname()
    {
        return $this->belongsTo(Hostname::class);
    }


    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
