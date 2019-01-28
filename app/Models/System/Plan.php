<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'pricing',
        'limit_users',
        'limit_documents',
        'documents_active', 
        'locked', 
    ];

    public function setDocumentsActiveAttribute($value)
    {
        $this->attributes['documents_active'] = (is_null($value))?null:json_encode($value);
    }

    public function getDocumentsActiveAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }


    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
