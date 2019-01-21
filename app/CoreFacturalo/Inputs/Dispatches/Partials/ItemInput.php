<?php

namespace App\CoreFacturalo\Inputs\Dispatches\Partials;

use App\Models\Tenant\Item;

class ItemInput
{
    public static function set($inputs)
    {
        if(key_exists('items', $inputs)) {
            $items = [];
            foreach ($inputs['items'] as $row)
            {
                $item = Item::find($row['item_id']);
                $quantity = $row['quantity'];

                $items[] = [
                    'item' => [
                        'description' => $item->description,
                        'item_type_id' => '01',
                        'internal_id' => $item->internal_id,
                        'item_code' => $item->item_code,
                        'item_code_gs1' => $item->item_code_gs1,
                        'unit_type_id' => $item->unit_type_id,
                    ],
                    'quantity' => $quantity,
                ];
            }

            return $items;
        }
        return null;
    }
}