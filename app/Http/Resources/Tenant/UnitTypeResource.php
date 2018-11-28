<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class UnitTypeResource extends JsonResource
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
            'catalog_id' => $this->catalog_id,
            'code' => $this->code,
            'description' => $this->description,
            'active' => (bool)$this->active,
            'tribute_code' => $this->tribute_code,
            'tribute_name' => $this->tribute_name,
            'rate' => $this->rate,
            'level' => $this->level,
            'type' => $this->type,
        ];
    }
}