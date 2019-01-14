<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\ProcessType;

class Summary extends ModelTenant
{
    protected $with = ['user', 'soap_type', 'state_type', 'documents'];

    protected $fillable = [
        'user_id',
        'external_id',
        'soap_type_id',
        'state_type_id',
        'process_type_code',
        'ubl_version',
        'date_of_issue',
        'date_of_reference',
        'identifier',
        'filename',
        'ticket',
        'has_ticket',
        'has_cdr',
    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'date_of_reference' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function state_type()
    {
        return $this->belongsTo(StateType::class);
    }

//    public function process_type()
//    {
//        return $this->belongsTo(ProcessType::class);
//    }

    public function documents()
    {
        return $this->hasMany(SummaryDocument::class);
    }

    public function getDownloadExternalXmlAttribute()
    {
        return route('tenant.summaries.download_external', ['type' => 'xml', 'external_id' => $this->external_id]);
    }

    public function getDownloadExternalPdfAttribute()
    {
        return route('tenant.summaries.download_external', ['type' => 'pdf', 'external_id' => $this->external_id]);
    }

    public function getDownloadExternalCdrAttribute()
    {
        return route('tenant.summaries.download_external', ['type' => 'cdr', 'external_id' => $this->external_id]);
    }
}