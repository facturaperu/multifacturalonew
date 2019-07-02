<?php

Route::prefix('services')->group(function() {
    Route::get('ruc/{number}', 'ServiceController@ruc');
    Route::get('dni/{number}', 'ServiceController@dni');
});
