<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Models\Tenant\Catalogs\Code;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'item_type_id',
        'internal_id',
        'unit_type_id',
        'description',
        'unit_price',
    ];

    public function item_type()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function unit_type()
    {
        return $this->belongsTo(Code::class, 'unit_type_id');
    }
}