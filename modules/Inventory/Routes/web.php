<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware('auth')->group(function() {

            Route::prefix('warehouses')->group(function () {
                Route::get('/', 'WarehouseController@index')->name('warehouses.index');
                Route::get('records', 'WarehouseController@records');
                Route::get('columns', 'WarehouseController@columns');
                Route::get('tables', 'WarehouseController@tables');
                Route::get('record/{warehouse}', 'WarehouseController@record');
                Route::post('/', 'WarehouseController@store');
                Route::get('initialize', 'WarehouseController@initialize');
            });

            Route::prefix('inventory')->group(function () {
                Route::get('/', 'InventoryController@index')->name('inventory.index');
                Route::get('records', 'InventoryController@records');
                Route::get('columns', 'InventoryController@columns');
                Route::get('tables', 'InventoryController@tables');
                Route::get('record/{inventory}', 'InventoryController@record');
                Route::post('/', 'InventoryController@store');
                Route::post('move', 'InventoryController@move');
                Route::post('remove', 'InventoryController@remove');
            });

        });
    });
}