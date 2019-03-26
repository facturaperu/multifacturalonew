<?php

namespace App\Models\Tenant; 


class ItemWarehouse extends ModelTenant
{ 

    protected $table = 'item_warehouse';

    protected $fillable = [
        'item_id',
        'warehouse_id', 
        'stock', 
    ];
 
}