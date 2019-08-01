<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('paymentonline')->group(function() {
    Route::get('/{token}', 'PaymentOnlineController@index');
    Route::post('/', 'PaymentOnlineController@store')->name('paymentonline.store');
});
