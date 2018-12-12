<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\System\ItemType;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Models\Tenant\Catalogs\Code;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use UsesTenantConnection;

    protected $with = ['item_type', 'unit_type', 'currency_type'];
    protected $fillable = [
        'description',
        'item_type_id',
        'internal_id',
        'item_code',
        'item_code_gs1',
        'unit_type_id',
        'currency_type_id',
        'unit_price',
        'has_isc',
        'system_isc_type_id',
        'percentage_isc',
        'suggested_price'
    ];

    public function item_type()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function unit_type()
    {
        return $this->belongsTo(Code::class, 'unit_type_id');
    }

    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class);
    }

    public function system_isc_type()
    {
        return $this->belongsTo(SystemIscType::class);
    }
}