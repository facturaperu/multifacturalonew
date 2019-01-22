<?php

namespace App\CoreFacturalo\Transforms\Documents\Partials;

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
                    'description' => $row['descripcion'],
                    'item_type_id' => TransformFunctions::valueKeyInArray($row, 'codigo_tipo_item', '01'),
                    'item_code' => TransformFunctions::valueKeyInArray($row, 'codigo_producto_sunat'),
                    'item_code_gs1' => TransformFunctions::valueKeyInArray($row, 'codigo_producto_gsl'),
                    'unit_type_id' => strtoupper($row['unidad_de_medida']),

                    'quantity' => TransformFunctions::valueKeyInArray($row, 'cantidad'),
                    'unit_value' => TransformFunctions::valueKeyInArray($row, 'valor_unitario'),
                    'price_type_id' => TransformFunctions::valueKeyInArray($row, 'codigo_tipo_precio'),
                    'unit_price' => TransformFunctions::valueKeyInArray($row, 'precio_unitario'),

                    'affectation_igv_type_id' => TransformFunctions::valueKeyInArray($row, 'codigo_tipo_afectacion_igv'),
                    'total_base_igv' => TransformFunctions::valueKeyInArray($row, 'total_base_igv'),
                    'percentage_igv' => TransformFunctions::valueKeyInArray($row, 'porcentaje_igv'),
                    'total_igv' => TransformFunctions::valueKeyInArray($row, 'total_igv'),

                    'system_isc_type_id' => TransformFunctions::valueKeyInArray($row, 'codigo_tipo_sistema_isc'),
                    'total_base_isc' => TransformFunctions::valueKeyInArray($row, 'total_base_isc'),
                    'percentage_isc' => TransformFunctions::valueKeyInArray($row, 'porcentaje_isc'),
                    'total_isc' => TransformFunctions::valueKeyInArray($row, 'total_isc'),

                    'total_base_other_taxes' => TransformFunctions::valueKeyInArray($row, 'total_base_otros_impuestos'),
                    'percentage_other_taxes' => TransformFunctions::valueKeyInArray($row, 'porcentaje_otros_impuestos'),
                    'total_other_taxes' => TransformFunctions::valueKeyInArray($row, 'total_otros_impuestos'),

                    'total_taxes' => TransformFunctions::valueKeyInArray($row, 'total_impuestos'),
                    'total_value' => TransformFunctions::valueKeyInArray($row, 'total_valor_item'),
                    'total' => TransformFunctions::valueKeyInArray($row, 'total_item'),

                    'attributes' => ItemAttributeTransform::transform($row),
                    'discounts' => DiscountTransform::transform($row),
                    'charges' => ChargeTransform::transform($row),
                ];
            }

            return $items;
        }
        return null;
    }
}