<?php

namespace App\CoreFacturalo\Transforms\TransApi\Dispatches\Partials;

use App\Models\Tenant\Item;
use Exception;

class ItemInput
{
    public static function transform($inputs)
    {
        $items = array_key_exists('items', $inputs)?$inputs['items']:[];

        if(count($items) > 0) {
            $transform_items = [];
            foreach ($items as $row)
            {
                $item = Item::updateOrCreate(
                    [
                        'internal_id' => $row['codigo_interno']
                    ],
                    [
                        'description' => $row['descripcion'],
                        'item_type_id' => '01',
                        'item_code' => array_key_exists('codigo_producto_sunat', $row)?$row['codigo_producto_sunat']:null,
                        'item_code_gs1' => array_key_exists('codigo_producto_gsl', $row)?$row['codigo_producto_gsl']:null,
                        'unit_type_id' => strtoupper($row['unidad_de_medida']),
                        'currency_type_id' => 'PEN',
                        'unit_price' => array_key_exists('precio_unitario', $row)?$row['precio_unitario']:0,
                    ]
                );
                $item_description = $row['descripcion'];
                $quantity = $row['cantidad'];

                $transform_items[] = [
                    'item_id' => $item->id,
                    'item' => [
                        'item_type_id' => $item->item_type_id,
                        'internal_id' => $item->internal_id,
                        'item_code' => $item->item_code,
                        'item_code_gs1' => $item->item_code_gs1,
                        'unit_type_id' => $item->unit_type_id,
                    ],
                    'item_description' => $item_description,
                    'quantity' => $quantity,
                ];
            }

            return $transform_items;
        } else {
            throw new Exception("Se requiere al menos un ítem para continuar.");
        }
    }
}