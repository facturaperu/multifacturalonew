<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\Code;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use UsesTenantConnection;

    protected $with = ['item', 'unit_type', 'affectation_igv_type', 'system_isc_type', 'price_type'];
    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'item_id',
        'item_description',
        'item_code',
        'item_code_gsl',
        'unit_type_id',
        'quantity',
        'unit_value',

        'affectation_igv_type_id',
        'total_base_igv',
        'percentage_igv',
        'total_igv',

        'system_isc_type_id',
        'total_base_isc',
        'percentage_isc',
        'total_isc',

        'total_base_other_taxes',
        'percentage_other_taxes',
        'total_other_taxes',
        'total_taxes',

        'price_type_id',
        'unit_price',
        'unit_price_free',

        'total_value',
        'total',

        'attributes',
        'charges',
        'discounts'
    ];

    protected $casts = [
        'date_of_document' => 'date',
    ];

    public function getAttributesAttribute($value)
    {
        return (object) json_decode($value);
    }

    public function setAttributesAttribute($value)
    {
        $this->attributes['attributes'] = json_encode($value);
    }

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

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function item()
    {
        return $this->belongsTo(Document::class);
    }

    public function unit_type()
    {
        return $this->belongsTo(Code::class, 'unit_type_id');
    }

    public function affectation_igv_type()
    {
        return $this->belongsTo(Code::class, 'affectation_igv_type_id');
    }

    public function system_isc_type()
    {
        return $this->belongsTo(Code::class, 'system_isc_type');
    }

    public function price_type()
    {
        return $this->belongsTo(Code::class, 'price_type_id');
    }
}