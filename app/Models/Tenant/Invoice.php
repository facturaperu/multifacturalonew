<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use UsesTenantConnection;

    public $timestamps = false;

    protected $fillable = [
        'operation_type_code',
        'date_of_due',
        'base_global_discount',
        'percentage_global_discount',
        'total_global_discount',
        'total_free',
        'total_prepayment',
        'purchase_order',
        'detraction',
        'perception',
        'prepayments'
    ];

    protected $casts = [
        'date_of_due' => 'date',
    ];

    public function getDetractionAttribute($value)
    {
        return (object) json_decode($value);
    }

    public function setDetractionAttribute($value)
    {
        $this->attributes['detraction'] = json_encode($value);
    }

    public function getPerceptionAttribute($value)
    {
        return (object) json_decode($value);
    }

    public function setPerceptionAttribute($value)
    {
        $this->attributes['perception'] = json_encode($value);
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
}