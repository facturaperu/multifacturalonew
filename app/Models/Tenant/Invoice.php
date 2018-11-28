<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\Code;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use UsesTenantConnection;

    protected $with = ['operation_type'];
    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'operation_type_id',
        'date_of_due',
        'total_free',
        'total_global_discount',
        'total_discount',
        'total_charge',
        'total_prepayment',
        'total_value',

        'charges',
        'discounts',
        'perception',
        'detraction',
        'prepayments'
    ];

    protected $casts = [
        'date_of_due' => 'date',
    ];

    public function getChargesAttribute($value)
    {
        return (object) json_decode($value);
    }

    public function setChargesAttribute($value)
    {
        $this->attributes['charges'] = json_encode($value);
    }

    public function getDiscountsAttribute($value)
    {
        return (object) json_decode($value);
    }

    public function setDiscountsAttribute($value)
    {
        $this->attributes['discounts'] = json_encode($value);
    }

    public function getPerceptionAttribute($value)
    {
        return (object) json_decode($value);
    }

    public function setPerceptionAttribute($value)
    {
        $this->attributes['perception'] = json_encode($value);
    }

    public function getDetractionAttribute($value)
    {
        return (object) json_decode($value);
    }

    public function setDetractionAttribute($value)
    {
        $this->attributes['detraction'] = json_encode($value);
    }

    public function getPrepaymentsAttribute($value)
    {
        return (object) json_decode($value);
    }

    public function setPrepaymentsAttribute($value)
    {
        $this->attributes['prepayments'] = json_encode($value);
    }

    public function document()
    {
        return $this->hasOne(Document::class);
    }

    public function operation_type()
    {
        return $this->belongsTo(Code::class, 'operation_type_id');
    }
}