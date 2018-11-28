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
            'internal_id' => $this->internal_id,
            'description' => $this->description,
            'unit_price' => $this->unit_price,
            'unit_type_id' => $this->unit_type_id,
        ];
    }
}