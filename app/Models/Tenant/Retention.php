<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Catalogs\RetentionType;

class Retention extends ModelTenant
{
    protected $with = ['user', 'soap_type', 'state_type', 'document_type', 'series',
                       'retention_type', 'documents'];

    protected $fillable = [
        'user_id',
        'external_id',
        'establishment_id',
        'establishment',
        'soap_type_id',
        'state_type_id',
        'ubl_version',
        'document_type_id',
        'series',
        'number',
        'date_of_issue',
        'time_of_issue',
        'supplier_id',
        'supplier',
        'retention_type_id',
        'observations',
        'total_retention',
        'total',

        'legends',
        'optional',

        'filename',
        'hash',

        'has_xml',
        'has_pdf',
        'has_cdr'
    ];

    protected $casts = [
        'date_of_issue' => 'date',
    ];

    public function getEstablishmentAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setEstablishmentAttribute($value)
    {
        $this->attributes['establishment'] = (is_null($value))?null:json_encode($value);
    }

    public function getSupplierAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setSupplierAttribute($value)
    {
        $this->attributes['supplier'] = (is_null($value))?null:json_encode($value);
    }

    public function getLegendsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setLegendsAttribute($value)
    {
        $this->attributes['legends'] = (is_null($value))?null:json_encode($value);
    }

    public function getOptionalAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setOptionalAttribute($value)
    {
        $this->attributes['optional'] = (is_null($value))?null:json_encode($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function state_type()
    {
        return $this->belongsTo(StateType::class);
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function series()
    {
        return $this->belongsTo(Series::class);
    }

    public function retention_type()
    {
        return $this->belongsTo(RetentionType::class);
    }

    public function documents()
    {
        return $this->hasMany(RetentionDocument::class);
    }

    public function getNumberFullAttribute()
    {
        return $this->series.'-'.$this->number;
    }
}