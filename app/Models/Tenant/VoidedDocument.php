<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class VoidedDocument extends Model
{
    use UsesTenantConnection;

    public $timestamps = false;

    protected $fillable = [
        'voided_id',
        'document_id',
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