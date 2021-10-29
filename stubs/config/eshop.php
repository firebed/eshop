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

    'logo'               => 'storage/images/logo.png',
    'logo_width'         => 306,
    'logo_height'        => 76,
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
    | Filtering Manufacturers
    |--------------------------------------------------------------------------
    |
    | Here you may specify whether the customers can filter products by
    | manufacturers on category, sales and search pages. This option is global
    | and will affect the mentioned pages.
    |
    */

    'filter_manufacturers' => false,

    /*
    |--------------------------------------------------------------------------
    | Product Image Optimization
    |--------------------------------------------------------------------------
    |
    | This options controls the default image appearance on all pages. When set
    | to true, all the products' images will be stretched to cover the container's
    | aspect ratio. If it's set to false then the images will be centered on
    | the parent container.
    |
    */

    'stretch_product_images' => true,
];