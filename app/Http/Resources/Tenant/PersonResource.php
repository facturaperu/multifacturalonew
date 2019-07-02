<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
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
            'type' => $this->type,
            'identity_document_type_id' => $this->identity_document_type_id,
            'number' => $this->number,
            'name' => $this->name,
            'trade_name' => $this->trade_name,
            'addresses' => collect($this->addresses)->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'trade_name' => $row->trade_name,
                    'country_id' => $row->country_id,
                    'location_id' => !is_null($row->location_id)?$row->location_id:[],
                    'address' => $row->address,
                    'phone' => $row->phone,
                    'email' => $row->email,
                    'main' => (bool)$row->main,
                ];
            }),
        ];
    }
}