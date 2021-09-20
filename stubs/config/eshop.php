<?php

return [
    'invoicing' => env('INVOICING', true),

    'locales' => [
        'el' => 'Ελληνικά',
        'en' => 'English'
    ],

    'logo'               => 'storage/images/logo.png',
    'default_locale'     => env('COUNTRY_LOCALE', 'en'),
    'country'            => env('COUNTRY', 'us'),
    'currency'           => env('CURRENCY', 'USD'),
    'currency_symbol'    => env('CURRENCY_SYMBOL', '$'),
    'group_separator'    => env('GROUP_SEPARATOR', ','),
    'decimal_separator'  => env('DECIMAL_SEPARATOR', '.'),
    'currency_placement' => env('CURRENCY_PLACEMENT', 's'),
    'sign_placement'     => env('SIGN_PLACEMENT', 'p'),

    'google_analytics_id' => env('GOOGLE_ANALYTICS_ID'),

    'paypal_live_client_id'     => env('PAYPAL_LIVE_CLIENT_ID'),
    'paypal_live_client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET'),

    'paypal_sandbox_client_id'     => env('PAYPAL_SANDBOX_CLIENT_ID'),
    'paypal_sandbox_client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET'),

    'style' => [
        'header-top'        => '',
        'header-main'       => '',
        'header-bottom'     => '',
        'header-categories' => ''
    ]
];