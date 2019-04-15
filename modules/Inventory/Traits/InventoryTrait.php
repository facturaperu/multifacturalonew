<?php

namespace Modules\Inventory\Traits;

use App\Models\Tenant\Establishment;
use App\Models\Tenant\Item;
use Modules\Inventory\Models\Warehouse;

trait InventoryTrait
{
    public function optionsEstablishment()
    {
        $records = Establishment::all();

        return collect($records)->transform(function($row) {
            return  [
                'id' => $row->id,
                'description' => $row->description
            ];
        });
    }


    public function optionsItem()
    {
        $records = Item::where('item_type_id', '01')->get();

        return collect($records)->transform(function($row) {
            return  [
                'id' => $row->id,
                'description' => $row->description
            ];
        });
    }

    public function optionsWarehouse()
    {
        $records = Warehouse::all();

        return collect($records)->transform(function($row) {
            return  [
                'id' => $row->id,
                'description' => $row->description
            ];
        });
    }

    public function findWarehouse()
    {
        $establishment = auth()->user()->establishment;
        return Warehouse::firstOrCreate(['establishment_id' => $establishment->id],
            ['description' => 'Almacén '.$establishment->description]);
    }
}