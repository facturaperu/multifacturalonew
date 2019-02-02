<?php

Route::get('services/exchange_rate', 'Tenant\Api\ServiceController@exchangeRateTest');
Route::post('services/search_exchange_rate', 'Tenant\Api\ServiceController@exchangeRate');
$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($hostname) {
    Route::domain($hostname->fqdn)->group(function() {
        Route::middleware('auth:api')->group(function() {
            Route::post('documents', 'Tenant\Api\DocumentController@store');
            Route::post('summaries', 'Tenant\Api\SummaryController@store');
            Route::post('voided', 'Tenant\Api\VoidedController@store');
            Route::post('retentions', 'Tenant\Api\RetentionController@store');
            Route::post('dispatches', 'Tenant\Api\DispatchController@store');
            Route::post('documents/send', 'Tenant\Api\DocumentController@send');
            Route::post('summaries/status', 'Tenant\Api\SummaryController@status');
            Route::post('voided/status', 'Tenant\Api\VoidedController@status');
            Route::get('services/ruc/{number}', 'Tenant\Api\ServiceController@ruc');
            Route::get('services/dni/{number}', 'Tenant\Api\ServiceController@dni');
        });
        Route::post('services/validate_cpe', 'Tenant\Api\ServiceController@validateCpe');
        Route::post('services/consult_status', 'Tenant\Api\ServiceController@consultStatus');
        Route::post('services/consult_cdr_status', 'Tenant\Api\ServiceController@consultCdrStatus');

    });
}