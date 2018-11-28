<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\Code;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use UsesTenantConnection;

    protected $with = ['user', 'establishment', 'soap_type', 'state_type', 'group', 'document_type', 'series',
                       'customer', 'currency_type', 'details', 'invoice', 'note'];

    protected $fillable = [
        'user_id',
        'establishment_id',
        'external_id',
        'soap_type_id',
        'state_type_id',
        'ubl_version',
        'group_id',
        'document_type_id',
        'series_id',
        'number',
        'date_of_issue',
        'time_of_issue',
        'customer_id',
        'currency_type_id',
        'total_other_charges',
        'total_exportation',
        'total_taxed',
        'total_unaffected',
        'total_exonerated',
        'total_igv',
        'total_base_isc',
        'total_isc',
        'total_base_other_taxes',
        'total_other_taxes',
        'total_discount',
        'total',

        'legends',
        'guides',
        'additional_documents',
        'optional',

        'filename',
        'hash',
        'qr',

        'has_xml',
        'has_pdf',
        'has_cdr'
    ];

    protected $casts = [
        'date_of_issue' => 'date',
    ];

    public function getLegendsAttribute($value)
    {
        return (object)json_decode($value);
    }

    public function setLegendsAttribute($value)
    {
        $this->attributes['legends'] = json_encode($value);
    }

    public function getGuidesAttribute($value)
    {
        return (object)json_decode($value);
    }

    public function setGuidesAttribute($value)
    {
        $this->attributes['guides'] = json_encode($value);
    }

    public function getAdditionalDocumentsAttribute($value)
    {
        return (object)json_decode($value);
    }

    public function setAdditionalDocumentsAttribute($value)
    {
        $this->attributes['additional_documents'] = json_encode($value);
    }

    public function getOptionalAttribute($value)
    {
        return (object)json_decode($value);
    }

    public function setOptionalAttribute($value)
    {
        $this->attributes['optional'] = json_encode($value);
    }

    public function getNumberFullAttribute()
    {
        return $this->series . '-' . $this->number;
    }

    public function getNumberToLetterAttribute()
    {
        $legends = $this->legends;
        $legend = collect($legends)->where('code', '1000')->first();
        return $legend->description;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function state_type()
    {
        return $this->belongsTo(StateType::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function document_type()
    {
        return $this->belongsTo(Code::class, 'document_type_id');
    }

    public function series()
    {
        return $this->belongsTo(Series::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function currency_type()
    {
        return $this->belongsTo(Code::class, 'currency_type_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function note()
    {
        return $this->hasOne(Note::class);
    }

    public function details()
    {
        return $this->hasMany(Detail::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function getVoidedAttribute()
    {
        if ($this->group_id === '01') {
            return Voided::whereHas('documents', function ($query) {
                                $query->where('document_id', $this->id);
                            })
                            ->whereIn('state_type_id', ['03', '05'])
                            ->first();
        }

        return Summary::whereHas('documents', function ($query) {
                            $query->where('document_id', $this->id);
                        })
                        ->whereIn('state_type_id', ['03', '05'])
                        ->where('process_type_id', 3)
                        ->first();
    }

    public function scopeWhereUser($query)
    {
        return $query->where('user_id', cache('selected_user_id'));
    }

    public function scopeWhereProcessType($query, $process_type_id)
    {
        if($process_type_id === 1) {
            return $query->where('state_type_id', '01');
        }
        return $query->where('state_type_id', '13');
    }

    public function getDownloadXmlAttribute()
    {
        return route('tenant.documents.download', ['type' => 'xml', 'document' => $this->id]);
    }

    public function getDownloadPdfAttribute()
    {
        return route('tenant.documents.download', ['type' => 'pdf', 'document' => $this->id]);
    }

    public function getDownloadCdrAttribute()
    {
        return route('tenant.documents.download', ['type' => 'cdr', 'document' => $this->id]);
    }

    public function getDownloadExternalXmlAttribute()
    {
        return route('tenant.documents.download_external', ['type' => 'xml', 'external_id' => $this->external_id]);
    }

    public function getDownloadExternalPdfAttribute()
    {
        return route('tenant.documents.download_external', ['type' => 'pdf', 'external_id' => $this->external_id]);
    }

    public function getDownloadExternalCdrAttribute()
    {
        return route('tenant.documents.download_external', ['type' => 'cdr', 'external_id' => $this->external_id]);
    }

    public function getDocumentTypeDescriptionAttribute()
    {
        $document_type = Code::byCatalogAndCode('01', $this->document_type_code);
        return $document_type->description;
    }
}