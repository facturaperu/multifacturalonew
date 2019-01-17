<?php

namespace App\CoreFacturalo\Transforms\TransApi\Documents\Partials;

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
                $attributes = ItemAttributeInput::transform($row);
                $discounts = DiscountInput::transform($row);
                $charges = ChargeInput::transform($row);

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
                        'currency_type_id' => $inputs['codigo_tipo_moneda'],
                        'unit_price' => $row['precio_unitario'],
                    ]
                );
                $item_description = $row['descripcion'];
                $quantity = $row['cantidad'];
                $unit_value = $row['valor_unitario'];
                $price_type_id = $row['codigo_tipo_precio'];
                $unit_price = $row['precio_unitario'];
                $affectation_igv_type_id = $row['codigo_tipo_afectacion_igv'];
                $total_base_igv = $row['total_base_igv'];
                $percentage_igv = $row['porcentaje_igv'];
                $total_igv = $row['total_igv'];
                $system_isc_type_id = array_key_exists('codigo_tipo_sistema_isc', $row)?$row['codigo_tipo_sistema_isc']:null;
                $total_base_isc = array_key_exists('total_base_isc', $row)?$row['total_base_isc']:0;
                $percentage_isc = array_key_exists('porcentaje_isc', $row)?$row['porcentaje_isc']:0;
                $total_isc = array_key_exists('total_isc', $row)?$row['total_isc']:0;
                $total_base_other_taxes = array_key_exists('total_base_otros_impuestos', $row)?$row['total_base_otros_impuestos']:0;
                $percentage_other_taxes = array_key_exists('porcentaje_otros_impuestos', $row)?$row['porcentaje_otros_impuestos']:0;
                $total_other_taxes = array_key_exists('total_otros_impuestos', $row)?$row['total_otros_impuestos']:0;
                $total_taxes = $row['total_impuestos'];
                $total_value = $row['total_valor_item'];
                $total = $row['total_item'];


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
                    'unit_value' => $unit_value,
                    'price_type_id' => $price_type_id,
                    'unit_price' => $unit_price,
                    'affectation_igv_type_id' => $affectation_igv_type_id,
                    'total_base_igv' => $total_base_igv,
                    'percentage_igv' => $percentage_igv,
                    'total_igv' => $total_igv,
                    'system_isc_type_id' => $system_isc_type_id,
                    'total_base_isc' => $total_base_isc,
                    'percentage_isc' => $percentage_isc,
                    'total_isc' => $total_isc,
                    'total_base_other_taxes' => $total_base_other_taxes,
                    'percentage_other_taxes' => $percentage_other_taxes,
                    'total_other_taxes' => $total_other_taxes,
                    'total_taxes' => $total_taxes,
                    'total_value' => $total_value,
                    'total' => $total,
                    'attributes' => $attributes,
                    'discounts' => $discounts,
                    'charges' => $charges,
                ];
            }

            return $transform_items;
        } else {
            throw new Exception("Se requiere al menos un ítem para continuar.");
        }
    }
}