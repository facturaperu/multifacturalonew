<?php

namespace App\CoreFacturalo\Inputs\Documents\Partials;

use App\Models\Tenant\Item;

class ItemInput
{
    public static function set($inputs)
    {
        if(key_exists('items', $inputs)) {
            $items = [];
            foreach ($inputs['items'] as $row) {
                $attributes = ItemAttributeInput::set($row);
                $discounts = DiscountInput::set($row);
                $charges = ChargeInput::set($row);

                $item = Item::find($row['item_id']);

                $items[] = [
                    'item' => [
                        'description' => $item->descripcion,
                        'item_type_id' => '01',
                        'internal_id' => $item->internal_id,
                        'item_code' => $item->item_code,
                        'item_code_gs1' => $item->item_code_gs1,
                        'unit_type_id' => $item->unit_type_id,
                    ],
                    'quantity' => $row['quantity'],
                    'unit_value' => $row['unit_value'],
                    'price_type_id' => $row['price_type_id'],
                    'unit_price' => $row['unit_price'],
                    'affectation_igv_type_id' => $row['affectation_igv_type_id'],
                    'total_base_igv' => $row['total_base_igv'],
                    'percentage_igv' => $row['percentage_igv'],
                    'total_igv' => $row['total_igv'],
                    'system_isc_type_id' => $row['system_isc_type_id'],
                    'total_base_isc' => $row['total_base_isc'],
                    'percentage_isc' => $row['percentage_isc'],
                    'total_isc' => $row['$total_isc'],
                    'total_base_other_taxes' => $row['total_base_other_taxes'],
                    'percentage_other_taxes' => $row['percentage_other_taxes'],
                    'total_other_taxes' => $row['$total_other_taxes'],
                    'total_taxes' => $row['$total_taxes'],
                    'total_value' => $row['total_value'],
                    'total' => $row['total'],

                    'attributes' => $attributes,
                    'discounts' => $discounts,
                    'charges' => $charges,
                ];
            }

            return $items;
        }
        return null;
    }
}