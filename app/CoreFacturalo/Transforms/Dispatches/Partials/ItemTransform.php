<?php

namespace App\CoreFacturalo\Transforms\Dispatches\Partials;

use App\CoreFacturalo\Transforms\Functions;

class ItemTransform
{
    public static function transform($inputs)
    {
        if(key_exists('items', $inputs)) {
            $items = [];
            foreach ($inputs['items'] as $row)
            {
                $item_id = Functions::updateOrCreateItem($row);

                $items[] = [
                    'item_id' => $item_id,
                    'quantity' => $row['cantidad'],
                ];
            }

            return $items;
        }
        return null;
    }
}