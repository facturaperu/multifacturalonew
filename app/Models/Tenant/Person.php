<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\IdentityDocumentType;

class Person extends ModelTenant
{
    protected $table = 'persons';
    protected $with = ['identity_document_type', 'addresses'];

    protected $fillable = [
        'type',
        'identity_document_type_id',
        'number',
        'name',
        'trade_name',
//        'country_id',
//        'department_id',
//        'province_id',
//        'district_id',
//        'address',
//        'email',
//        'telephone',
    ];

    public function identity_document_type()
    {
        return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id');
    }

    public function addresses()
    {
        return $this->hasMany(PersonAddress::class);
    }

//    public function country()
//    {
//        return $this->belongsTo(Country::class);
//    }
//
//    public function department()
//    {
//        return $this->belongsTo(Department::class);
//    }
//
//    public function province()
//    {
//        return $this->belongsTo(Province::class);
//    }
//
//    public function district()
//    {
//        return $this->belongsTo(District::class);
//    }

    public function scopeWhereType($query, $type)
    {
        return $query->where('type', $type);
    }

//    public function getAddressFullAttribute()
//    {
//        $address = trim($this->address);
//        $address = ($address === '-' || $address === '')?'':$address.' ,';
//        if ($address === '') {
//            return '';
//        }
//        return "{$address} {$this->department->description} - {$this->province->description} - {$this->district->description}";
//    }
//
//    public function more_address()
//    {
//        return $this->hasMany(PersonAddress::class);
//    }
}