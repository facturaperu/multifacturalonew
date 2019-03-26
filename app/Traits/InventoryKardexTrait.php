<?php

namespace App\Traits;
use App\Models\Tenant\InventoryKardex; 
use App\Models\Tenant\Item; 
 


trait InventoryKardexTrait
{
    
    public function saveInventoryKardex($model, $item_id, $warehouse_id, $quantity) {
        
        $inventory_kardex = $model->inventory_kardex()->create([ 
            'date_of_issue' => date('Y-m-d'),
            'item_id' => $item_id,
            'warehouse_id' => $warehouse_id, 
            'quantity' => $quantity,
        ]);

        return $inventory_kardex;

    }

    public function updateStock($item_id, $warehouse_id, $quantity, $is_sale){

        $item = Item::find($item_id);
        $item->stock = ($is_sale) ? $item->stock - $quantity : $item->stock + $quantity;
        $item->save();
        
    }

}
