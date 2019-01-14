<?php

namespace App\Http\Resources\Tenant\Catalogs;

use Illuminate\Http\Resources\Json\JsonResource;

class CodeResource extends JsonResource
{
//$table->char('catalog_id', 2);
//$table->string('code');
//$table->string('description');
//$table->string('short')->nullable();
//$table->string('symbol')->nullable();
//$table->boolean('exportation')->nullable();
//$table->boolean('free')->nullable();
//$table->decimal('percentage', 10, 2)->nullable();
//$table->boolean('base')->nullable();
//$table->enum('type', ['discount', 'charge'])->nullable();
//$table->enum('level', ['item', 'global'])->nullable();
//$table->boolean('active');
//
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
            'short' => $this->short,
            'symbol' => $this->symbol,
            'exportation' => $this->exportation,
            'free' => $this->free,
            'percentage' => $this->percentage,
            'type' => $this->type,
        ];
    }
}