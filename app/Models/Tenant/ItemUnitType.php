<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\UnitType;

class ItemUnitType extends ModelTenant
{

    // protected $table = "item_unit_types";

    public $timestamps = false;

    protected $fillable = [ 
        'item_id', 
        'unit_type_id',
        'quantity_unit',
        'price_1',
        'price_2',  
        'price_3',  
    ];
 
 
    public function unit_type()
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
 
}