<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use UsesTenantConnection;

    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'item_description',
        'item_code',
        'unit_type_code',
        'carriage_plate',
        'quantity',
        'unit_value',
        'price_type_code',
        'unit_price',
        'affectation_igv_type_code',
        'total_igv',
        'percentage_igv',
        'system_isc_type_code',
        'total_isc',
        'charge_type_code',
        'charge_percentage',
        'total_charge',
        'discount_type_code',
        'discount_percentage',
        'total_discount',
        'total_value',
        'total',
        'additional',
        'first_housing_contract_number',
        'first_housing_credit_date'
    ];

    protected $casts = [
        'date_of_document' => 'date',
    ];

    public function getAdditionalAttribute($value)
    {
        return (object) json_decode($value);
    }

    public function setAdditionalAttribute($value)
    {
        $this->attributes['additional'] = json_encode($value);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}