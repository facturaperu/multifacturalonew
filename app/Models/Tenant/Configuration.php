<?php

namespace App\Models\Tenant;

class Configuration extends ModelTenant
{
    protected $fillable = [
        'send_auto', 
        'cron', 
        'stock',
        'amount_plastic_bag_taxes',
        'sunat_alternate_server'
    ];
}