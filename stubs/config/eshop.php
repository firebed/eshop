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

    'google-analytics-id' => env('GOOGLE_ANALYTICS_ID'),
];