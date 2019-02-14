<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function boot() {
        if (env('FORCE_HTTPS', false)) URL::forceScheme('https');
    }
    
    public function register() {
        
    }
}