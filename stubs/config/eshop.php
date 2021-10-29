<?php

return [
    'invoicing' => env('INVOICING', true),

    'locales' => [
        'el' => 'Ελληνικά',
        'en' => 'English'
    ],

    'countries' => [
        'el' => 'GR',
        'en' => 'US'
    ],

    'cc' => [

    ],

    'logo' => [
        'path'   => 'storage/images/logo.png',
        'width'  => 306,
        'height' => 76
    ],

    'country'            => env('COUNTRY', 'US'),
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

    /*
    |--------------------------------------------------------------------------
    | Filtering Products
    |--------------------------------------------------------------------------
    |
    | Here you may specify the customer's search experience.
    | Through these options you can choose which filters should be usable
    | when searching for products. These options are global and will affect
    | the all the pages that use product filtering.
    |
    */

    'filter' => [
        'manufacturers' => false
    ],

    /*
    |--------------------------------------------------------------------------
    | Product Image Optimization
    |--------------------------------------------------------------------------
    |
    | This options control the default image appearance on all pages.
    | These will allow you to stretch the product images so that they cover the
    | parent container's aspect ratio, as well as the minimum width / height
    | values on uploading images.
    |
    */

    'product' => [
        'image' => [
            'cover'         => true,
            'upload_width'  => 1000,
            'upload_height' => 1000
        ]
    ],
];