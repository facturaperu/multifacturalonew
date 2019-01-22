<?php

namespace App\CoreFacturalo\Inputs\Dispatches\Partials;

use App\Models\Tenant\Item;

class ItemInput
{
    public static function set($inputs, $service)
    {
        if(array_key_exists('items', $inputs)) {
            $items = [];
            foreach ($inputs['items'] as $row) {
                if($service === 'api') {
                    $item_id = self::findItem($row);
                } else {
                    $item_id = $row['item_id'];
                }

                $item = Item::find($item_id);

                $items[] = [
                    'item_id' => $item->id,
                    'item' => [
                        'description' => $item->description,
                        'item_type_id' => $item->item_type_id,
                        'internal_id' => $item->internal_id,
                        'item_code' => $item->item_code,
                        'item_code_gs1' => $item->item_code_gs1,
                        'unit_type_id' => $item->unit_type_id,
                    ],
                    'quantity' => $row['quantity'],
                ];
            }

            return $items;
        }
        return null;
    }

    public static function findItem($data)
    {
        $item = Item::where('internal_id', $data['internal_id'])->first();

        return $item->id;
    }
}