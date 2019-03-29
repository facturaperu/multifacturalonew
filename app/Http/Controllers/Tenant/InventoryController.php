<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\InventoryCollection;
use App\Http\Resources\Tenant\InventoryResource;
use App\Models\Tenant\Item;
use App\Models\Tenant\ItemWarehouse;
use App\Models\Tenant\Warehouse;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index() {
        return view('tenant.inventories.index');
    }
    
    public function columns() {
        return [
            'item_id' => 'Producto'
        ];
    }
    
    public function records(Request $request) {
        $records = ItemWarehouse::with(['item', 'warehouse'])->orderBy('item_id');
        
        return new InventoryCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function tables() {
        return [
            'items' => Item::where('item_type_id', '01')->get(),
            'warehouses' => Warehouse::all()
        ];
    }

    public function record($id)
    {
        $record = new InventoryResource(ItemWarehouse::with(['item', 'warehouse'])->findOrFail($id));

        return $record;
    }

    public function store(Request $request)
    {
        $item_id = $request->input('item_id');
        $warehouse_id = $request->input('warehouse_id');
        $quantity = $request->input('quantity');

        $item_warehouse = ItemWarehouse::firstOrNew(['item_id' => $item_id,
                                                     'warehouse_id' => $warehouse_id]);
        if($item_warehouse->id) {
            return [
                'success' => false,
                'message' => 'El producto ya se encuentra registrado en el almacén indicado.'
            ];
        }

        $item_warehouse->stock = $quantity;
        $item_warehouse->save();

        return  [
            'success' => true,
            'message' => 'Producto registrado en almacén'
        ];
    }

    public function move(Request $request)
    {
        $id = $request->input('id');
        $item_id = $request->input('item_id');
        $warehouse_new_id = $request->input('warehouse_new_id');
        $quantity = $request->input('quantity');
        $quantity_move = $request->input('quantity_move');

        //Transaction
        $item_warehouse_new = ItemWarehouse::firstOrNew(['item_id' => $item_id,
                                                         'warehouse_id' => $warehouse_new_id]);

        $stock_new = ($item_warehouse_new)?$item_warehouse_new->stock + $quantity_move:$quantity_move;
        $item_warehouse_new->stock = $stock_new;
        $item_warehouse_new->save();

        $item_warehouse = ItemWarehouse::find($id);
        $item_warehouse->stock = (float) $quantity - (float)$quantity_move;
        $item_warehouse->save();

        return  [
            'success' => true,
            'message' => 'Producto trasladado con éxito'
        ];
    }
}
