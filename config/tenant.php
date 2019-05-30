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
    'is_client' => env('IS_CLIENT', false),
    'token_server' => env('TOKEN_SERVER'),
    'url_server' => env('URL_SERVER'),
    'recreate_document' => env('RECREATE_DOCUMENT', false),
    'pdf_template' => env('PDF_TEMPLATE', 'default'),
    'pdf_template_footer' => env('PDF_TEMPLATE_FOOTER', false),
    'pdf_name_regular' => env('PDF_NAME_REGULAR', false),
    'pdf_name_bold' => env('PDF_NAME_BOLD', false),
];
