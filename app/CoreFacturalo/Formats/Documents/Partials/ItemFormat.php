<?php

namespace App\CoreFacturalo\Formats\Documents\Partials;

use App\Models\Tenant\Item;

class ItemFormat
{
    public static function format($inputs)
    {
        $items = [];
        foreach ($inputs['items'] as $row)
        {
            $attributes = array_key_exists('attributes', $row)?$row['attributes']:null;
            $discounts = array_key_exists('discounts', $row)?$row['discounts']:null;
            $charges = array_key_exists('charges', $row)?$row['charges']:null;

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
}