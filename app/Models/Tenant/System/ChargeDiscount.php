<?php

namespace App\Models\Tenant\System;

use App\Models\Tenant\Catalogs\ChargeDiscountType;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class ChargeDiscount extends Model
{
    use UsesTenantConnection;

    public $timestamps = false;
    protected $with = ['charge_discount_type'];
    protected $fillable = [
        'charge_discount_type_id',
        'type',
        'level',
        'base',
        'description',
        'percentage',
    ];

    public function charge_discount_type()
    {
        return $this->belongsTo(ChargeDiscountType::class);
    }

//    public function scopeWhereLevel($query, $level)
//    {
//        if ($level) {
//            return $query->whereHas('charge_discount_type', function($q) use($level) {
//                $q->where('level', $level);
//            });
//        }
//
//        return $query;
//    }

//    public function scopeWhereType($query, $type)
//    {
//        if ($type) {
//            return $query->where('type', $type);
//        }
//
//        return $query;
//    }
}