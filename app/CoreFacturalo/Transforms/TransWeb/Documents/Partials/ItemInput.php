<?php

namespace App\CoreFacturalo\Transforms\TransWeb\Documents\Partials;

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

                $item = Item::find($row['item_id']);

                $item_description = $row['item_description'];
                $quantity = $row['quantity'];
                $unit_value = $row['unit_value'];
                $price_type_id = $row['price_type_id'];
                $unit_price = $row['unit_price'];
                $affectation_igv_type_id = $row['affectation_igv_type_id'];
                $total_base_igv = $row['total_base_igv'];
                $percentage_igv = $row['percentage_igv'];
                $total_igv = $row['total_igv'];
                $system_isc_type_id = $row['system_isc_type_id'];
                $total_base_isc = $row['total_base_isc'];
                $percentage_isc = $row['percentage_isc'];
                $total_isc = $row['total_isc'];
                $total_base_other_taxes = $row['total_base_other_taxes'];
                $percentage_other_taxes = $row['percentage_other_taxes'];
                $total_other_taxes = $row['total_other_taxes'];
                $total_taxes = $row['total_taxes'];
                $total_value = $row['total_value'];
                $total = $row['total'];

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