<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($hostname) {
    Route::domain($hostname->fqdn)->group(function() {

        Auth::routes();

        Route::get('search', 'Tenant\SearchController@index')->name('search.index');
        Route::get('buscar', 'Tenant\SearchController@index')->name('search.index');
        Route::get('search/tables', 'Tenant\SearchController@tables');
        Route::post('search', 'Tenant\SearchController@store');

        Route::get('downloads/{model}/{type}/{external_id}/{format?}', 'Tenant\DownloadController@downloadExternal')->name('tenant.download.external_id');
        Route::get('print/{model}/{external_id}/{format?}', 'Tenant\DownloadController@toPrint');

        Route::middleware(['auth', 'module'])->group(function() {
            Route::get('/', function () {
                return redirect()->route('tenant.documents.create');
            });
            Route::get('dashboard', 'Tenant\HomeController@index')->name('tenant.dashboard');
            Route::get('catalogs', 'Tenant\CatalogController@index')->name('tenant.catalogs.index');
            Route::get('advanced', 'Tenant\AdvancedController@index')->name('tenant.advanced.index');

            //Company
            Route::get('companies/create', 'Tenant\CompanyController@create')->name('tenant.companies.create');
            Route::get('companies/tables', 'Tenant\CompanyController@tables');
            Route::get('companies/record', 'Tenant\CompanyController@record');
            Route::post('companies', 'Tenant\CompanyController@store');
            Route::post('companies/uploads', 'Tenant\CompanyController@uploadFile');

            //Configurations
            Route::get('configurations/create', 'Tenant\ConfigurationController@create')->name('tenant.configurations.create');
            Route::get('configurations/record', 'Tenant\ConfigurationController@record');
            Route::post('configurations', 'Tenant\ConfigurationController@store');

            //Certificates
            Route::get('certificates/record', 'Tenant\CertificateController@record');
            Route::post('certificates/uploads', 'Tenant\CertificateController@uploadFile');
            Route::delete('certificates', 'Tenant\CertificateController@destroy');

            //Establishments
            Route::get('establishments', 'Tenant\EstablishmentController@index')->name('tenant.establishments.index');
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
            Route::get('series/records/{establishment}', 'Tenant\SeriesController@records');
            Route::get('series/create', 'Tenant\SeriesController@create');
            Route::get('series/tables', 'Tenant\SeriesController@tables');
            Route::post('series', 'Tenant\SeriesController@store');
            Route::delete('series/{series}', 'Tenant\SeriesController@destroy');

            //Users
            Route::get('users', 'Tenant\UserController@index')->name('tenant.users.index');
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
            Route::post('items/import', 'Tenant\ItemController@import');

            //Customers
//            Route::get('customers', 'Tenant\CustomerController@index')->name('tenant.customers.index');
//            Route::get('customers/columns', 'Tenant\CustomerController@columns');
//            Route::get('customers/records', 'Tenant\CustomerController@records');
//            Route::get('customers/tables', 'Tenant\CustomerController@tables');
//            Route::get('customers/record/{item}', 'Tenant\CustomerController@record');
//            Route::post('customers', 'Tenant\CustomerController@store');
//            Route::delete('customers/{customer}', 'Tenant\CustomerController@destroy');
//
//            //Suppliers
//            Route::get('suppliers', 'Tenant\SupplierController@index')->name('tenant.suppliers.index');
//            Route::get('suppliers/columns', 'Tenant\SupplierController@columns');
//            Route::get('suppliers/records', 'Tenant\SupplierController@records');
//            Route::get('suppliers/tables', 'Tenant\SupplierController@tables');
//            Route::get('suppliers/record/{item}', 'Tenant\SupplierController@record');
//            Route::post('suppliers', 'Tenant\SupplierController@store');
//            Route::delete('suppliers/{supplier}', 'Tenant\SupplierController@destroy');

            //Persons
            Route::get('persons/columns', 'Tenant\PersonController@columns');
            Route::get('persons/tables', 'Tenant\PersonController@tables');
            Route::get('persons/{type}', 'Tenant\PersonController@index')->name('tenant.persons.index');
            Route::get('persons/{type}/records', 'Tenant\PersonController@records');
            Route::get('persons/record/{person}', 'Tenant\PersonController@record');
            Route::post('persons', 'Tenant\PersonController@store');
            Route::delete('persons/{person}', 'Tenant\PersonController@destroy');
            Route::post('persons/import', 'Tenant\PersonController@import');

            //Documents
            Route::get('documents', 'Tenant\DocumentController@index')->name('tenant.documents.index');
            Route::get('documents/columns', 'Tenant\DocumentController@columns');
            Route::get('documents/records', 'Tenant\DocumentController@records');
            Route::get('documents/create', 'Tenant\DocumentController@create')->name('tenant.documents.create');
            Route::get('documents/tables', 'Tenant\DocumentController@tables');
            Route::get('documents/record/{document}', 'Tenant\DocumentController@record');
            Route::post('documents', 'Tenant\DocumentController@store');
            Route::get('documents/send/{document}', 'Tenant\DocumentController@send');
            Route::get('documents/consult_cdr/{document}', 'Tenant\DocumentController@consultCdr');
            Route::post('documents/email', 'Tenant\DocumentController@email');
            Route::get('documents/note/{document}', 'Tenant\NoteController@create');
            Route::get('documents/item/tables', 'Tenant\DocumentController@item_tables');
            Route::get('documents/table/{table}', 'Tenant\DocumentController@table');

            //Summaries
            Route::get('summaries', 'Tenant\SummaryController@index')->name('tenant.summaries.index');
            Route::get('summaries/records', 'Tenant\SummaryController@records');
            Route::post('summaries/documents', 'Tenant\SummaryController@documents');
            Route::post('summaries', 'Tenant\SummaryController@store');
            Route::get('summaries/status/{summary}', 'Tenant\SummaryController@status');

            //Voided
            Route::get('voided', 'Tenant\VoidedController@index')->name('tenant.voided.index');
            Route::get('voided/columns', 'Tenant\VoidedController@columns');
            Route::get('voided/records', 'Tenant\VoidedController@records');
            Route::post('voided', 'Tenant\VoidedController@store');
//            Route::get('voided/download/{type}/{voided}', 'Tenant\VoidedController@download')->name('tenant.voided.download');
            Route::get('voided/status/{voided}', 'Tenant\VoidedController@status');
//            Route::get('voided/ticket/{voided_id}/{group_id}', 'Tenant\VoidedController@ticket');

            //Retentions
            Route::get('retentions', 'Tenant\RetentionController@index')->name('tenant.retentions.index');
            Route::get('retentions/columns', 'Tenant\RetentionController@columns');
            Route::get('retentions/records', 'Tenant\RetentionController@records');
            Route::get('retentions/create', 'Tenant\RetentionController@create')->name('tenant.retentions.create');
            Route::get('retentions/tables', 'Tenant\RetentionController@tables');
            Route::get('retentions/record/{retention}', 'Tenant\RetentionController@record');
            Route::post('retentions', 'Tenant\RetentionController@store');
            Route::delete('retentions/{retention}', 'Tenant\RetentionController@destroy');
            Route::get('retentions/document/tables', 'Tenant\RetentionController@document_tables');
            Route::get('retentions/table/{table}', 'Tenant\RetentionController@table');

            //Dispatches
            Route::get('dispatches', 'Tenant\DispatchController@index')->name('tenant.dispatches.index');
            Route::get('dispatches/columns', 'Tenant\DispatchController@columns');
            Route::get('dispatches/records', 'Tenant\DispatchController@records');
            Route::get('dispatches/create', 'Tenant\DispatchController@create');
            Route::post('dispatches/tables', 'Tenant\DispatchController@tables');
            Route::post('dispatches', 'Tenant\DispatchController@store');

            Route::get('reports', 'Tenant\ReportController@index')->name('tenant.reports.index');
            Route::post('reports/search', 'Tenant\ReportController@search')->name('tenant.search');
            Route::post('reports/pdf', 'Tenant\ReportController@pdf')->name('tenant.report_pdf');
            Route::post('reports/excel', 'Tenant\ReportController@excel')->name('tenant.report_excel');

            Route::get('reports/purchases', 'Tenant\ReportPurchaseController@index')->name('tenant.reports.purchases.index');
            Route::post('reports/purchases/search', 'Tenant\ReportPurchaseController@search')->name('tenant.reports.purchases.search');
            Route::post('reports/purchases/pdf', 'Tenant\ReportPurchaseController@pdf')->name('tenant.report.purchases.pdf');
            Route::post('reports/purchases/excel', 'Tenant\ReportPurchaseController@excel')->name('tenant.report.purchases.report_excel');

            Route::get('reports/inventories', 'Tenant\ReportInventoryController@index')->name('tenant.reports.inventories.index');
            Route::post('reports/inventories/search', 'Tenant\ReportInventoryController@search')->name('tenant.reports.inventories.search');
            Route::post('reports/inventories/pdf', 'Tenant\ReportInventoryController@pdf')->name('tenant.report.inventories.pdf');
            Route::post('reports/inventories/excel', 'Tenant\ReportInventoryController@excel')->name('tenant.report.inventories.report_excel');
            
            Route::get('reports/kardex', 'Tenant\ReportKardexController@index')->name('tenant.reports.kardex.index');
            Route::post('reports/kardex/search', 'Tenant\ReportKardexController@search')->name('tenant.reports.kardex.search');
            Route::post('reports/kardex/pdf', 'Tenant\ReportKardexController@pdf')->name('tenant.report.kardex.pdf');
            Route::post('reports/kardex/excel', 'Tenant\ReportKardexController@excel')->name('tenant.report.kardex.report_excel');

            Route::post('options/delete_documents', 'Tenant\OptionController@deleteDocuments');

            Route::get('services/ruc/{number}', 'Tenant\Api\ServiceController@ruc');
            Route::get('services/dni/{number}', 'Tenant\Api\ServiceController@dni');
            Route::post('services/exchange_rate', 'Tenant\Api\ServiceController@exchange_rate');
            Route::post('services/search_exchange_rate', 'Tenant\Api\ServiceController@searchExchangeRateByDate');

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

            //Banks
            Route::get('banks/records', 'Tenant\BankController@records');
            Route::get('banks/record/{bank}', 'Tenant\BankController@record');
            Route::post('banks', 'Tenant\BankController@store');
            Route::delete('banks/{bank}', 'Tenant\BankController@destroy');

            //Exchange Rates
            Route::get('exchange_rates/records', 'Tenant\ExchangeRateController@records');
            Route::post('exchange_rates', 'Tenant\ExchangeRateController@store');

            //Currency Types
            Route::get('currency_types/records', 'Tenant\CurrencyTypeController@records');
            Route::get('currency_types/record/{currency_type}', 'Tenant\CurrencyTypeController@record');
            Route::post('currency_types', 'Tenant\CurrencyTypeController@store');
            Route::delete('currency_types/{currency_type}', 'Tenant\CurrencyTypeController@destroy');

            //Perceptions
            Route::get('perceptions', 'Tenant\PerceptionController@index')->name('tenant.perceptions.index');
            Route::get('perceptions/columns', 'Tenant\PerceptionController@columns');
            Route::get('perceptions/records', 'Tenant\PerceptionController@records');
            Route::get('perceptions/create', 'Tenant\PerceptionController@create')->name('tenant.perceptions.create');
            Route::get('perceptions/tables', 'Tenant\PerceptionController@tables');
            Route::get('perceptions/record/{perception}', 'Tenant\PerceptionController@record');
            Route::post('perceptions', 'Tenant\PerceptionController@store');
            Route::delete('perceptions/{perception}', 'Tenant\PerceptionController@destroy');
            Route::get('perceptions/item/tables', 'Tenant\PerceptionController@item_tables');


            //Tribute Concept Type
            Route::get('tribute_concept_types/records', 'Tenant\TributeConceptTypeController@records');
            Route::get('tribute_concept_types/record/{id}', 'Tenant\TributeConceptTypeController@record');
            Route::post('tribute_concept_types', 'Tenant\TributeConceptTypeController@store');
            Route::delete('tribute_concept_types/{id}', 'Tenant\TributeConceptTypeController@destroy');

            //purchases
            Route::get('purchases', 'Tenant\PurchaseController@index')->name('tenant.purchases.index');
            Route::get('purchases/columns', 'Tenant\PurchaseController@columns');
            Route::get('purchases/records', 'Tenant\PurchaseController@records');
            Route::get('purchases/create', 'Tenant\PurchaseController@create')->name('tenant.purchases.create');
            Route::get('purchases/tables', 'Tenant\PurchaseController@tables');
            Route::get('purchases/table/{table}', 'Tenant\PurchaseController@table');
            Route::post('purchases', 'Tenant\PurchaseController@store');
            Route::get('purchases/record/{document}', 'Tenant\PurchaseController@record');
            // Route::get('documents/send/{document}', 'Tenant\DocumentController@send');
            // Route::get('documents/consult_cdr/{document}', 'Tenant\DocumentController@consultCdr');
            // Route::post('documents/email', 'Tenant\DocumentController@email');
            // Route::get('documents/note/{document}', 'Tenant\NoteController@create');
            Route::get('purchases/item/tables', 'Tenant\PurchaseController@item_tables');
            // Route::get('documents/table/{table}', 'Tenant\DocumentController@table');

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
            Route::get('clients/tables', 'System\ClientController@tables');
            Route::get('clients/charts', 'System\ClientController@charts');
            Route::post('clients', 'System\ClientController@store');
            Route::delete('clients/{client}', 'System\ClientController@destroy');
            Route::post('clients/password/{client}', 'System\ClientController@password');

            //Planes
            Route::get('plans', 'System\PlanController@index')->name('system.plans.index');
            Route::get('plans/records', 'System\PlanController@records');
            Route::get('plans/tables', 'System\PlanController@tables');
            Route::get('plans/record/{plan}', 'System\PlanController@record');
            Route::post('plans', 'System\PlanController@store');
            Route::delete('plans/{plan}', 'System\PlanController@destroy');



            //Users
            Route::get('users/create', 'System\UserController@create')->name('system.users.create');
            Route::get('users/record', 'System\UserController@record');
            Route::post('users', 'System\UserController@store');

            Route::get('services/ruc/{number}', 'System\ServiceController@ruc');
        });
    });
}
