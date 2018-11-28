<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'user_id',
        'process_type_id',
        'soap_type_id',
        'state_type_id',
        'ubl_version',
        'date_of_issue',
        'date_of_reference',
        'identifier',
        'filename',
        'has_ticket',
        'ticket',
        'has_cdr',
    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'date_of_reference' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function process_type()
    {
        return $this->belongsTo(ProcessType::class);
    }

    public function state_type()
    {
        return $this->belongsTo(StateType::class);
    }

    public function documents()
    {
        return $this->hasMany(SummaryDocument::class);
    }

    public function scopeWhereUser($query)
    {
        return $query->where('user_id', cache('selected_user_id'));
    }

    public function getDownloadCdrAttribute()
    {
        return route('tenant.summaries.download', ['type' => 'cdr', 'id' => $this->id]);
    }

    public function getDownloadXmlAttribute()
    {
        return route('tenant.summaries.download', ['type' => 'xml', 'id' => $this->id]);
    }
}