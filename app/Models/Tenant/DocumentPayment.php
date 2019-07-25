<?php

namespace App\Models\Tenant;
use App\Models\Tenant\Catalogs\CurrencyType;

class DocumentPayment extends ModelTenant
{
    protected $with = ['payment_method_type', 'card_brand','currency_type'];
    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'currency_type_id',
        'date_of_payment',
        'payment_method_type_id',
        'has_card',
        'card_brand_id',
        'number',
        'payment',
    ];

    protected $casts = [
        'date_of_payment' => 'date',
    ];

    public function payment_method_type()
    {
        return $this->belongsTo(PaymentMethodType::class);
    }

    public function card_brand()
    {
        return $this->belongsTo(CardBrand::class);
    }
    
    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }
}