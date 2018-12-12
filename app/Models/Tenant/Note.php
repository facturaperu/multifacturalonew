<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\Code;
use App\Models\Tenant\Catalogs\NoteCreditType;
use App\Models\Tenant\Catalogs\NoteDebitType;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use UsesTenantConnection;

    protected $with = ['note_type'];
    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'note_type',
        'note_credit_type_id',
        'note_debit_type_id',
        'description',
        'affected_document_id',
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

    public function affected_document()
    {
        return $this->belongsTo(Document::class, 'affected_document_id');
    }

    public function note_credit_type()
    {
        return $this->belongsTo(NoteCreditType::class);
    }

    public function note_debit_type()
    {
        return $this->belongsTo(NoteDebitType::class);
    }
}