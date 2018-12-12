<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\Province;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Models\Tenant\Catalogs\Code;
use Illuminate\Database\Eloquent\Model;

class Establishment extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'description',
        'country_id',
        'department_id',
        'province_id',
        'district_id',
        'address',
        'email',
        'phone',
        'code',
    ];

    public function country()
    {
        return $this->belongsTo(Code::class, 'country_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function getAddressFullAttribute()
    {
        $address = ($this->address != '-')? $this->address.' ,' : '';
        return "{$address} {$this->department->description} - {$this->province->description} - {$this->district->description}";
    }
}