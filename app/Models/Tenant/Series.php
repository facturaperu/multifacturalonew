<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\Code;

class Series extends ModelTenant
{
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

    public function setNumberAttribute($value)
    {
        $this->attributes['number'] = strtoupper($value);
    }
}