<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Catalogs\RetentionType;

class Dispatch extends ModelTenant
{
//$table->increments('id');
//$table->unsignedInteger('user_id');
//$table->uuid('external_id');
//$table->unsignedInteger('establishment_id');
//$table->json('establishment');
//$table->char('soap_type_id', 2);
//$table->char('state_type_id', 2);
//$table->string('ubl_version');
//$table->char('document_type_id', 2);
//$table->char('series', 4);
//$table->integer('number');
//$table->date('date_of_issue');
//$table->time('time_of_issue');
//$table->unsignedInteger('customer_id');
//$table->json('customer');
//$table->json('shipment');
//
//$table->json('legends')->nullable();
//$table->json('optional')->nullable();
//
//$table->string('filename')->nullable();
//$table->string('hash')->nullable();
//$table->boolean('has_xml')->default(false);
//$table->boolean('has_pdf')->default(false);
//$table->boolean('has_cdr')->default(false);
//$table->timestamps();
//
//$table->foreign('user_id')->references('id')->on('users');
//$table->foreign('establishment_id')->references('id')->on('establishments');
//$table->foreign('soap_type_id')->references('id')->on('soap_types');
//$table->foreign('state_type_id')->references('id')->on('state_types');
//$table->foreign('document_type_id')->references('id')->on('document_types');
//$table->foreign('supplier_id')->references('id')->on('suppliers');


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
        'currency_type_id',
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

    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class);
    }

    public function documents()
    {
        return $this->hasMany(RetentionDocument::class);
    }

    public function getNumberFullAttribute()
    {
        return $this->series.'-'.$this->number;
    }

    public function getDownloadExternalXmlAttribute()
    {
        return route('tenant.retentions.download_external', ['type' => 'xml', 'external_id' => $this->external_id]);
    }

    public function getDownloadExternalPdfAttribute()
    {
        return route('tenant.retentions.download_external', ['type' => 'pdf', 'external_id' => $this->external_id]);
    }

    public function getDownloadExternalCdrAttribute()
    {
        return route('tenant.retentions.download_external', ['type' => 'cdr', 'external_id' => $this->external_id]);
    }
}