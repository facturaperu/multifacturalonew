<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'message',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}