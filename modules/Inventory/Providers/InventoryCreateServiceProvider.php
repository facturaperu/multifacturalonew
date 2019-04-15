<?php

namespace Modules\Inventory\Providers;

use App\Models\Tenant\Item; 
use Illuminate\Support\ServiceProvider;
use Modules\Inventory\Models\Inventory;
use Modules\Inventory\Traits\InventoryTrait;
use Modules\Inventory\Models\ItemWarehouse;

class InventoryCreateServiceProvider extends ServiceProvider
{
    use InventoryTrait;
    
    public function register()
    {

    }

    public function boot()
    {
        $this->createdItem();
        $this->inventory();
    }

    private function createdItem()
    {
        Item::created(function ($item) {
            $this->createInventory($item);
        });
    }

    private function createInventory($item)
    {
        $warehouse = $this->findWarehouse();
        Inventory::create([
            'type' => 1,
            'description' => 'Stock inicial',
            'item_id' => $item->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => ($item->stock) ? $item->stock : 0
        ]);
    }

    private function inventory()
    {
        Inventory::created(function ($inventory) {
            switch ($inventory->type) {
                case 1:
                    $this->createInventoryKardex($inventory, $inventory->warehouse_id);
                    $this->updateStock($inventory, $inventory->warehouse_id);
                    break;
                case 2:
                    //Origin
                    $this->createInventoryKardex($inventory, $inventory->warehouse_id, -1);
                    $this->updateStock($inventory, $inventory->warehouse_id, -1);
                    //Arrival
                    $this->createInventoryKardex($inventory, $inventory->warehouse_destination_id, 1);
                    $this->updateStock($inventory, $inventory->warehouse_destination_id, 1);
                    break;
                case 3:
                    $this->createInventoryKardex($inventory, $inventory->warehouse_id, -1);
                    $this->updateStock($inventory, $inventory->warehouse_id, -1);
                    break;
            }
        });
    }

    private function createInventoryKardex($inventory, $warehouse_id, $factor = 1)
    {
        $inventory_kardex = $inventory->inventory_kardex()->create([
            'date_of_issue' => date('Y-m-d'),
            'item_id' => $inventory->item_id,
            'warehouse_id' => $warehouse_id,
            'quantity' => $factor * $inventory->quantity,
        ]);

        return $inventory_kardex;
    }

    private function updateStock($inventory, $warehouse_id, $factor = 1)
    {
        $item_warehouse = ItemWarehouse::firstOrNew(['item_id' => $inventory->item_id, 'warehouse_id' => $warehouse_id]);
        $item_warehouse->stock = $item_warehouse->stock + ($factor * $inventory->quantity);
        $item_warehouse->save();
    }

}