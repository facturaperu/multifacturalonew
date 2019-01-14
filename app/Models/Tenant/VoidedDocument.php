<?php

namespace App\Models\Tenant;

class VoidedDocument extends ModelTenant
{
    protected $with = ['voided', 'document'];
    public $timestamps = false;

    protected $fillable = [
        'voided_id',
        'document_id',
        'description'
    ];

    public function voided()
    {
        return $this->belongsTo(Voided::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}