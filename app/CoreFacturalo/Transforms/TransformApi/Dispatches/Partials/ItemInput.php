<?php

namespace App\CoreFacturalo\Transforms\TransformApi\Dispatches\Partials;

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
                $item_type_id = '01';
                $internal_id = $row['codigo_interno'];
                $description = $row['descripcion'];
                $item_code = array_key_exists('codigo_producto_sunat', $row)?$row['codigo_producto_sunat']:null;
                $item_code_gs1 = array_key_exists('codigo_producto_gsl', $row)?$row['codigo_producto_gsl']:null;
                $unit_type_id = strtoupper($row['unidad_de_medida']);
                $quantity = $row['cantidad'];

                $transform_items[] = [
                    'item' => [
                        'description' => $description,
                        'item_type_id' => $item_type_id,
                        'internal_id' => $internal_id,
                        'item_code' => $item_code,
                        'item_code_gs1' => $item_code_gs1,
                        'unit_type_id' => $unit_type_id,
                    ],
                    'quantity' => $quantity,
                ];
            }

            return $transform_items;
        } else {
            throw new Exception("Se requiere al menos un ítem para continuar.");
        }
    }
}