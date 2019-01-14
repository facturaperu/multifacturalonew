<?php

namespace App\Models\Tenant;

class SummaryDocument extends ModelTenant
{
    protected $with = ['summary', 'document'];
    public $timestamps = false;

    protected $fillable = [
        'summary_id',
        'document_id',
    ];

    public function summary()
    {
        return $this->belongsTo(Summary::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}