<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\NoteCreditType;
use App\Models\Tenant\Catalogs\NoteDebitType;

class Note extends ModelTenant
{
    protected $with = ['affected_document'];
    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'note_type',
        'note_credit_type_code',
        'note_debit_type_code',
        'note_description',
        'affected_document_id',
    ];

    public function document()
    {
        return $this->hasOne(Document::class);
    }

    public function affected_document()
    {
        return $this->belongsTo(Document::class, 'affected_document_id');
    }

//    public function note_credit_type()
//    {
//        return $this->belongsTo(NoteCreditType::class);
//    }
//
//    public function note_debit_type()
//    {
//        return $this->belongsTo(NoteDebitType::class);
//    }
}