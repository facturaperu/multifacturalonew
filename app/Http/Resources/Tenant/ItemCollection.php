<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {
            return [
                'id' => $row->id,
                'description' => $row->description,
                'internal_id' => $row->internal_id,
                'item_code' => $row->item_code,
                'item_code_gs1' => $row->item_code_gs1,
                'currency_type_description' => $row->currency_type->description,
                'unit_price' => $row->unit_price,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}