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

    'notifications' => [
        'submitted' => [
            'cc' => [

            ]
        ]
    ],
    
    'logo' => [
        'path'   => '',
        'width'  => 0,
        'height' => 0
    ],
    
    'watermark' => '',
    
    'social' => [

    ],
    
    // Αναζήτηση Βάση ΑΦΜ Γ.Γ.Π.Σ.
    'ggps_username' => env('GGPS_USERNAME'),
    'ggps_password' => env('GGPS_PASSWORD'),

    'country'            => env('COUNTRY', 'US'),
    'currency'           => env('CURRENCY', 'USD'),
    'currency_symbol'    => env('CURRENCY_SYMBOL', '$'),
    'group_separator'    => env('GROUP_SEPARATOR', ','),
    'decimal_separator'  => env('DECIMAL_SEPARATOR', '.'),
    'currency_placement' => env('CURRENCY_PLACEMENT', 's'),
    'sign_placement'     => env('SIGN_PLACEMENT', 'p'),

    'google_analytics_id'  => env('GOOGLE_ANALYTICS_ID'),
    'google_conversion_id' => env('GOOGLE_CONVERSION_ID'),
    'google_event_id'      => env('GOOGLE_EVENT_ID'),

    'paypal_live_client_id'     => env('PAYPAL_LIVE_CLIENT_ID'),
    'paypal_live_client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET'),

    'paypal_sandbox_client_id'     => env('PAYPAL_SANDBOX_CLIENT_ID'),
    'paypal_sandbox_client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET'),
    
    'show_incomplete_carts' => false,
    
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
        'manufacturers' => true
    ],

    /*
    |--------------------------------------------------------------------------
    | Product Image Optimization
    |--------------------------------------------------------------------------
    |
    | These options control the default image appearance on all pages.
    | These will allow you to stretch the product images so that they cover the
    | parent container's aspect ratio, as well as the minimum width / height
    | values on uploading images.
    |
    */

    'product'         => [
        'image' => [
            'cover'         => false,
            'upload_width'  => 3000,
            'upload_height' => 3000
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Search bar
    |--------------------------------------------------------------------------
    |
    | This options allows you to modify the size of the search input. Possible
    | values are 'sm', 'lg' and null for default.
    |
    */
    'search_bar_size' => null,

    /*
    |--------------------------------------------------------------------------
    | Barcode generation
    |--------------------------------------------------------------------------
    |
    | These options control the barcode generation. These will allow
    | you to automatically create barcode when the barcode field is missing or
    | is empty and the characters to prepend to it. A separator will also be 
    | automatically added between each part of the barcode. If nested is set to
    | true then the product variants will include the parent product's id as
    | part of the barcode.
    |
    */
    'barcode'         => [
        'fill'      => true,
        'prepend'   => null,
        'separator' => '-',
        'nested'    => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Label printing options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the product label's width, height and margin in
    | millimeters (mm). Normally these values will be used for the first 
    | printing. After the print the user's preferences will be cached, and on 
    | subsequent prints the values stored in cache will be used instead.
    |
    */
    'label'           => [
        'font-size' => '9',
        'width'     => '35',
        'height'    => '24',
        'margin'    => '1',
    ]
];