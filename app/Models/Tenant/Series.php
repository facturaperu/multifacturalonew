<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Models\Tenant\Catalogs\Code;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use UsesTenantConnection;

    protected $table = 'series';

    protected $fillable = [
        'establishment_id',
        'document_type_id',
        'number',
    ];

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function document_type()
    {
        return $this->belongsTo(Code::class, 'document_type_id');
    }
}