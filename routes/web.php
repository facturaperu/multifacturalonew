<?php

//Route::domain(env('APP_URL_BASE'))->group(function() {
//
//    Route::get('login', 'System\LoginController@showLoginForm')->name('login');
//    Route::post('login', 'System\LoginController@login');
//    Route::post('logout', 'System\LoginController@logout')->name('logout');
//
//    Route::middleware('auth:admin')->group(function() {
////        Route::get('/', function () {
////            return view('system.dashboard');
////        });
//        Route::get('dashboard', 'System\HomeController@index')->name('system.dashboard');
//        Route::get('clients', 'System\ClientController@index');
//        Route::get('clients/records', 'System\ClientController@records');
//        Route::get('clients/create', 'System\ClientController@create');
////        Route::resource('clients', 'System\ClientController');
//
//        //Clients
//        Route::get('clients', 'System\ClientController@index')->name('clients.index');
//        Route::get('clients/create', 'System\ClientController@create')->name('clients.create');
//        Route::get('clients/tables', 'System\ClientController@tables');
//        Route::get('clients/record', 'System\ClientController@record');
//        Route::get('clients/records', 'System\ClientController@records');
//        Route::post('clients', 'System\ClientController@store');
//    });
//});

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($hostname) {
    Route::domain($hostname->fqdn)->group(function() {

        Auth::routes();

        Route::get('search', 'Tenant\SearchController@index')->name('search.index');
        Route::get('search/tables', 'Tenant\SearchController@tables');
        Route::post('search', 'Tenant\SearchController@store');
        Route::get('downloads/document/{type}/{external_id}', 'Tenant\DocumentController@downloadExternal')->name('tenant.documents.download_external');

        Route::middleware('auth')->group(function() {
            Route::get('/', function () {
                return redirect()->route('tenant.documents.create');
            });
            Route::get('dashboard', 'Tenant\HomeController@index')->name('tenant.dashboard');

            //Company
            Route::get('companies/create', 'Tenant\CompanyController@create')->name('tenant.companies.create');
            Route::get('companies/tables', 'Tenant\CompanyController@tables');
            Route::get('companies/record', 'Tenant\CompanyController@record');
            Route::post('companies', 'Tenant\CompanyController@store');
            Route::post('companies/uploads', 'Tenant\CompanyController@uploadFile');

            //Certificates
            Route::get('certificates/record', 'Tenant\CertificateController@record');
            Route::post('certificates/uploads', 'Tenant\CertificateController@uploadFile');
            Route::delete('certificates', 'Tenant\CertificateController@destroy');

            //Establishments
            Route::get('establishments/create', 'Tenant\EstablishmentController@create');
            Route::get('establishments/tables', 'Tenant\EstablishmentController@tables');
            Route::get('establishments/record/{establishment}', 'Tenant\EstablishmentController@record');
            Route::post('establishments', 'Tenant\EstablishmentController@store');
            Route::get('establishments/records', 'Tenant\EstablishmentController@records');
            Route::delete('establishments/{establishment}', 'Tenant\EstablishmentController@destroy');

            //Bank Accounts
            Route::get('bank_accounts', 'Tenant\BankAccountController@index')->name('tenant.bank_accounts.index');
            Route::get('bank_accounts/records', 'Tenant\BankAccountController@records');
            Route::get('bank_accounts/create', 'Tenant\BankAccountController@create');
            Route::get('bank_accounts/tables', 'Tenant\BankAccountController@tables');
            Route::get('bank_accounts/record/{bank_account}', 'Tenant\BankAccountController@record');
            Route::post('bank_accounts', 'Tenant\BankAccountController@store');
            Route::delete('bank_accounts/{bank_account}', 'Tenant\BankAccountController@destroy');

            //Series
            Route::get('series/records', 'Tenant\SeriesController@records');
            Route::get('series/create', 'Tenant\SeriesController@create');
            Route::get('series/tables', 'Tenant\SeriesController@tables');
            Route::post('series', 'Tenant\SeriesController@store');
            Route::delete('series/{series}', 'Tenant\SeriesController@destroy');

            //Users
            Route::get('users/create', 'Tenant\UserController@create')->name('tenant.users.create');
            Route::get('users/tables', 'Tenant\UserController@tables');
            Route::get('users/record/{user}', 'Tenant\UserController@record');
            Route::post('users', 'Tenant\UserController@store');
            Route::get('users/records', 'Tenant\UserController@records');
            Route::delete('users/{user}', 'Tenant\UserController@destroy');

            //ChargeDiscounts
            Route::get('charge_discounts', 'Tenant\ChargeDiscountController@index')->name('tenant.charge_discounts.index');
            Route::get('charge_discounts/records/{type}', 'Tenant\ChargeDiscountController@records');
            Route::get('charge_discounts/create', 'Tenant\ChargeDiscountController@create');
            Route::get('charge_discounts/tables/{type}', 'Tenant\ChargeDiscountController@tables');
            Route::get('charge_discounts/record/{charge}', 'Tenant\ChargeDiscountController@record');
            Route::post('charge_discounts', 'Tenant\ChargeDiscountController@store');
            Route::delete('charge_discounts/{charge}', 'Tenant\ChargeDiscountController@destroy');

            //Items
            Route::get('items', 'Tenant\ItemController@index')->name('tenant.items.index');
            Route::get('items/columns', 'Tenant\ItemController@columns');
            Route::get('items/records', 'Tenant\ItemController@records');
            Route::get('items/tables', 'Tenant\ItemController@tables');
            Route::get('items/record/{item}', 'Tenant\ItemController@record');
            Route::post('items', 'Tenant\ItemController@store');
            Route::delete('items/{item}', 'Tenant\ItemController@destroy');

            //Customers
            Route::get('customers', 'Tenant\CustomerController@index')->name('tenant.customers.index');
            Route::get('customers/columns', 'Tenant\CustomerController@columns');
            Route::get('customers/records', 'Tenant\CustomerController@records');
            Route::get('customers/tables', 'Tenant\CustomerController@tables');
            Route::get('customers/record/{item}', 'Tenant\CustomerController@record');
            Route::post('customers', 'Tenant\CustomerController@store');
            Route::delete('customers/{customer}', 'Tenant\CustomerController@destroy');

            //Documents
            Route::get('documents', 'Tenant\DocumentController@index')->name('tenant.documents.index');
            Route::get('documents/columns', 'Tenant\DocumentController@columns');
            Route::get('documents/records', 'Tenant\DocumentController@records');
            Route::get('documents/create', 'Tenant\DocumentController@create')->name('tenant.documents.create');
            Route::get('documents/tables', 'Tenant\DocumentController@tables');
            Route::get('documents/record/{document}', 'Tenant\DocumentController@record');
            Route::post('documents', 'Tenant\DocumentController@store');
            Route::post('documents/voided', 'Tenant\DocumentController@voided');
            Route::get('documents/to_print/{document}', 'Tenant\DocumentController@to_print');
            Route::get('documents/download/{type}/{document}', 'Tenant\DocumentController@download')->name('tenant.documents.download');
            Route::get('documents/send_xml/{document}', 'Tenant\DocumentController@send_xml');
            Route::post('documents/email', 'Tenant\DocumentController@email');
            Route::get('documents/note/{document}', 'Tenant\NoteController@create');
            Route::get('documents/item/tables', 'Tenant\DocumentController@item_tables');
            Route::get('documents/table/{table}', 'Tenant\DocumentController@table');

            //Summaries
            Route::get('summaries', 'Tenant\SummaryController@index')->name('tenant.summaries.index');
            Route::get('summaries/records', 'Tenant\SummaryController@records');
            Route::post('summaries/documents', 'Tenant\SummaryController@documents');
            Route::post('summaries', 'Tenant\SummaryController@store');
            Route::get('summaries/download/{type}/{summary}', 'Tenant\SummaryController@download')->name('tenant.summaries.download');
            Route::get('summaries/ticket/{summary}', 'Tenant\SummaryController@ticket');

            //Voided
            Route::get('voided/download/{type}/{voided}', 'Tenant\VoidedController@download')->name('tenant.voided.download');
            Route::get('voided/ticket/{voided_id}/{group_id}', 'Tenant\VoidedController@ticket');

            
            Route::get('reports', 'Tenant\ReportController@index')->name('tenant.reports.index');
            Route::post('reports/search', 'Tenant\ReportController@search')->name('tenant.search');
            Route::post('reports/pdf', 'Tenant\ReportController@pdf')->name('tenant.report_pdf');
            Route::post('reports/excel', 'Tenant\ReportController@excel')->name('tenant.report_excel');


            Route::post('options/delete_documents', 'Tenant\OptionController@deleteDocuments');


            Route::get('services/ruc/{number}', 'Tenant\Api\ServiceController@ruc');
            Route::get('services/dni/{number}', 'Tenant\Api\ServiceController@dni');

            //BUSQUEDA DE DOCUMENTOS
            // Route::get('busqueda', 'Tenant\SearchController@index')->name('search');
            // Route::post('busqueda', 'Tenant\SearchController@index')->name('search');

            //Codes
            Route::get('codes/records', 'Tenant\Catalogs\CodeController@records');
            Route::get('codes/tables', 'Tenant\Catalogs\CodeController@tables');
            Route::get('codes/record/{code}', 'Tenant\Catalogs\CodeController@record');
            Route::post('codes', 'Tenant\Catalogs\CodeController@store');
            Route::delete('codes/{code}', 'Tenant\Catalogs\CodeController@destroy');

            //Units
            Route::get('unit_types/records', 'Tenant\UnitTypeController@records');
            Route::get('unit_types/record/{code}', 'Tenant\UnitTypeController@record');
            Route::post('unit_types', 'Tenant\UnitTypeController@store');
            Route::delete('unit_types/{code}', 'Tenant\UnitTypeController@destroy');
        });
    });
} else {
    Route::domain(env('APP_URL_BASE'))->group(function() {

        Route::get('login', 'System\LoginController@showLoginForm')->name('login');
        Route::post('login', 'System\LoginController@login');
        Route::post('logout', 'System\LoginController@logout')->name('logout');

        Route::middleware('auth:admin')->group(function() {
            Route::get('/', function () {
                return redirect()->route('system.dashboard');
            });
            Route::get('dashboard', 'System\HomeController@index')->name('system.dashboard');

            //Clients
            Route::get('clients', 'System\ClientController@index')->name('system.clients.index');
            Route::get('clients/records', 'System\ClientController@records');
            Route::get('clients/create', 'System\ClientController@create');
            Route::post('clients', 'System\ClientController@store');
            Route::delete('clients/{client}', 'System\ClientController@destroy');
            Route::post('clients/password/{client}', 'System\ClientController@password');

            //Users
            Route::get('users/create', 'System\UserController@create')->name('system.users.create');
            Route::get('users/record', 'System\UserController@record');
            Route::post('users', 'System\UserController@store');
        });
    });
}
