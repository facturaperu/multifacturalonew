<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'internal_id' => $this->internal_id,
            'item_code' => $this->item_code,
            'item_code_gsl' => $this->item_code_gsl,
            'unit_price' => $this->unit_price,
            'currency_type_id' => $this->currency_type_id,
            'unit_type_id' => $this->unit_type_id,
            'has_isc' => (bool) $this->has_isc,
            'system_isc_type_id' => $this->system_isc_type_id,
            'percentage_isc' => $this->percentage_isc,
            'suggested_price' => $this->suggested_price,
        ];
    }
}