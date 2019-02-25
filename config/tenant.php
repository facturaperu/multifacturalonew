<?php

return [
    'app_url_base' => env('APP_URL_BASE'),
    'items_per_page' => env('ITEMS_PER_PAGE', 20),
    'password_change' => env('PASSWORD_CHANGE', false),
    'prefix_database' => env('PREFIX_DATABASE', 'tenancy'),
    'signature_note' => env('SIGNATURE_NOTE', 'FACTURALO'),
    'signature_uri' => env('SIGNATURE_URI', '#FACTURALO'),
    'force_https' => env('FORCE_HTTPS', false),
    'document_type_03_filter' => env('DOCUMENT_TYPE_03_FILTER', true),
    'server' => env('SEVER', false),
];
