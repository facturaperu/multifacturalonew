<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Models\Tenant\Catalogs\Code;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'identity_document_type_id',
        'number',
        'name',
        'trade_name',
        'soap_type_id',
        'soap_username',
        'soap_password',
        'certificate',
        'logo',
    ];

    public function identity_document_type()
    {
        return $this->belongsTo(Code::class, 'identity_document_type_id');
    }
}