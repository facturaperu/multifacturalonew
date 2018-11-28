<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class SummaryDocument extends Model
{
    use UsesTenantConnection;

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