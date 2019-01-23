<?php

namespace App\CoreFacturalo\Transforms\Api\Documents\Partials;

use App\CoreFacturalo\Transforms\Api\FunctionsTransform;
use App\Models\Tenant\Item;

class ItemTransform
{
    public static function transform($inputs)
    {
        if(key_exists('items', $inputs)) {
            $items = [];
            foreach ($inputs['items'] as $row) {
                $data = [
                    'internal_id' => $row['codigo_interno'],
                    'description' => $row['descripcion'],
                    'item_type_id' => FunctionsTransform::valueKeyInArray($row, 'codigo_tipo_item', '01'),
                    'item_code' => FunctionsTransform::valueKeyInArray($row, 'codigo_producto_sunat'),
                    'item_code_gs1' => FunctionsTransform::valueKeyInArray($row, 'codigo_producto_gsl'),
                    'unit_type_id' => strtoupper($row['unidad_de_medida']),
                    'currency_type_id' => $inputs['codigo_tipo_moneda'],
                    'unit_price' => $row['precio_unitario'],
                ];

                $item = self::updateOrCreateItem($data);

                $items[] = [
                    'item_id' => $item->id,
                    'quantity' => FunctionsTransform::valueKeyInArray($row, 'cantidad'),
                    'unit_value' => FunctionsTransform::valueKeyInArray($row, 'valor_unitario'),
                    'price_type_id' => FunctionsTransform::valueKeyInArray($row, 'codigo_tipo_precio'),
                    'unit_price' => FunctionsTransform::valueKeyInArray($row, 'precio_unitario'),

                    'affectation_igv_type_id' => FunctionsTransform::valueKeyInArray($row, 'codigo_tipo_afectacion_igv'),
                    'total_base_igv' => FunctionsTransform::valueKeyInArray($row, 'total_base_igv'),
                    'percentage_igv' => FunctionsTransform::valueKeyInArray($row, 'porcentaje_igv'),
                    'total_igv' => FunctionsTransform::valueKeyInArray($row, 'total_igv'),

                    'system_isc_type_id' => FunctionsTransform::valueKeyInArray($row, 'codigo_tipo_sistema_isc'),
                    'total_base_isc' => FunctionsTransform::valueKeyInArray($row, 'total_base_isc'),
                    'percentage_isc' => FunctionsTransform::valueKeyInArray($row, 'porcentaje_isc'),
                    'total_isc' => FunctionsTransform::valueKeyInArray($row, 'total_isc'),

                    'total_base_other_taxes' => FunctionsTransform::valueKeyInArray($row, 'total_base_otros_impuestos'),
                    'percentage_other_taxes' => FunctionsTransform::valueKeyInArray($row, 'porcentaje_otros_impuestos'),
                    'total_other_taxes' => FunctionsTransform::valueKeyInArray($row, 'total_otros_impuestos'),

                    'total_taxes' => FunctionsTransform::valueKeyInArray($row, 'total_impuestos'),
                    'total_value' => FunctionsTransform::valueKeyInArray($row, 'total_valor_item'),
                    'total' => FunctionsTransform::valueKeyInArray($row, 'total_item'),

                    'attributes' => ItemAttributeTransform::transform($row),
                    'discounts' => DiscountTransform::transform($row),
                    'charges' => ChargeTransform::transform($row),
                ];
            }

            return $items;
        }
        return null;
    }

    public static function updateOrCreateItem($data)
    {
        $item = Item::updateOrCreate(
            [
                'internal_id' => $data['internal_id'],
            ],
            [
                'description' => $data['description'],
                'item_type_id' => $data['item_type_id'],
                'item_code' => $data['item_code'],
                'item_code_gs1' => $data['item_code_gs1'],
                'unit_type_id' => $data['unit_type_id'],
                'currency_type_id' => $data['currency_type_id'],
                'unit_price' => $data['unit_price'],
            ]
        );

        return $item;
    }
}