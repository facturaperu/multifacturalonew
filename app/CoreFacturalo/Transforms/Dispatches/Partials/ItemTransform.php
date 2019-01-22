<?php

namespace App\CoreFacturalo\Transforms\Dispatches\Partials;

use App\CoreFacturalo\Transforms\TransformFunctions;

class ItemTransform
{
    public static function transform($inputs)
    {
        if(key_exists('items', $inputs)) {
            $items = [];
            foreach ($inputs['items'] as $row) {
                $items[] = [
                    'internal_id' => $row['codigo_interno'],
                    'quantity' => TransformFunctions::valueKeyInArray($row, 'cantidad'),
                ];
            }

            return $items;
        }
        return null;
    }
}