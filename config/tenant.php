<?php

return [
    'app_url_base' => env('APP_URL_BASE'),
    'items_per_page' => env('ITEMS_PER_PAGE', 20),
    'password_change' => env('PASSWORD_CHANGE', false),
    'prefix_database' => env('PREFIX_DATABASE', 'tenancy'),
    'signature_note' => env('SIGNATURE_NOTE', 'FACTURALO'),
    'signature_uri' => env('SIGNATURE_URI', '#FACTURALO'),
];
