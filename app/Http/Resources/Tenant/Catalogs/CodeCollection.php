<?php

namespace App\Http\Resources\Tenant\Catalogs;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CodeCollection extends ResourceCollection
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
                'catalog_description' => $row->catalog->description,
                'code' => $row->code,
                'description' => $row->description,
                'active' => $row->active,
                'tribute_code' => $row->tribute_code,
                'tribute_name' => $row->tribute_name,
                'rate' => $row->rate,
                'level' => $row->level,
                'type' => $row->type,
            ];
        });
    }
}