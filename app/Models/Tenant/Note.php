<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\Code;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use UsesTenantConnection;

    protected $with = ['note_type'];
    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'note_type_id',
        'description',
        'affected_document_type_code',
        'affected_document_series',
        'affected_document_number',
        'total_global_discount',
        'total_prepayment',

        'perception'
    ];

    public function getPerceptionAttribute($value)
    {
        return (object) json_decode($value);
    }

    public function setPerceptionAttribute($value)
    {
        $this->attributes['perception'] = json_encode($value);
    }

    public function document()
    {
        return $this->hasOne(Document::class);
    }

    public function note_type()
    {
        return $this->belongsTo(Code::class, 'note_type_id');
    }

}