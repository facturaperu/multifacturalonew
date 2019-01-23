<?php

namespace App\CoreFacturalo\Transforms\Api\Dispatches\Partials;

use App\CoreFacturalo\Transforms\Api\FunctionsTransform;

class ItemTransform
{
    public static function transform($inputs)
    {
        if(key_exists('items', $inputs)) {
            $items = [];
            foreach ($inputs['items'] as $row) {
                $items[] = [
                    'internal_id' => $row['codigo_interno'],
                    'quantity' => FunctionsTransform::valueKeyInArray($row, 'cantidad'),
                ];
            }

            return $items;
        }
        return null;
    }
}