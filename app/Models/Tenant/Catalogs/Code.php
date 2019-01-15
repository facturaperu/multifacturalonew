<?php

namespace App\Models\Tenant\Catalogs;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class Code extends ModelCatalog
{
    use UsesTenantConnection;

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'catalog_id',
        'code',
        'description',
        'short',
        'symbol',
        'exportation',
        'free',
        'percentage',
        'base',
        'type',
        'level',
        'active',
    ];

    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }

    public function scopeWhereCatalog($query, $catalog_id)
    {
        return $query->where('catalog_id', $catalog_id);
    }

    public function scopeWhereCodes($query, $code_ids)
    {
        return $query->whereIn('code', $code_ids);
    }

    public function scopeWhereType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeWhereLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public static function getCodeById($id)
    {
        $cod = self::find($id);
        if($cod) {
            return $cod->code;
        }
        return null;
    }

    public static function getDescriptionByCode($code)
    {
        $cod = Code::where('code', $code)->first();
        if($cod){
            return $cod->description;
        }
        return null;
    }

    public static function findByCode($code)
    {
        return Code::where('code', $code)->first();
    }
}