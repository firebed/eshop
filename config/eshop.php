<?php

return [
    'invoicing'      => env('INVOICING', true),
    'invoice_series' => null,

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
                env('EMAIL_CC')
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

    'country'            => env('COUNTRY', 'US'),
    'currency'           => env('CURRENCY', 'USD'),
    'currency_symbol'    => env('CURRENCY_SYMBOL', '$'),
    'group_separator'    => env('GROUP_SEPARATOR', ','),
    'decimal_separator'  => env('DECIMAL_SEPARATOR', '.'),
    'currency_placement' => env('CURRENCY_PLACEMENT', 's'),
    'sign_placement'     => env('SIGN_PLACEMENT', 'p'),

    'google_analytics_id'     => env('GOOGLE_ANALYTICS_ID'),
    'google_conversion_id'    => env('GOOGLE_CONVERSION_ID'),
    'google_conversion_label' => env('GOOGLE_CONVERSION_LABEL'),

    'paypal_live_client_id'     => env('PAYPAL_LIVE_CLIENT_ID'),
    'paypal_live_client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET'),

    'paypal_sandbox_client_id'     => env('PAYPAL_SANDBOX_CLIENT_ID'),
    'paypal_sandbox_client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET'),

    'show_incomplete_carts' => false,
    'auto_payments'         => false,
    'skroutz'               => false,
    'myshipping'            => false,

    'validate_phone_number' => false,

    /*
    |--------------------------------------------------------------------------
    | Cart events
    |--------------------------------------------------------------------------
    |
    | Here you may specify the options for cart management.
    | 
    | Change the abandoned notification option in order to switch on or off
    | the email notifications for abandoned carts.
    |
    | When the merge_abandoned is enabled and aside the cart from the email
    | there is another cart that can be fetched by using cookies or by
    | logging in the user, then these carts will be merged. After the merge,
    | the delete_abandoned option will specify which one to delete and which
    | one to keep. If its 'cookie' then the cart in cookie will be deleted,
    | otherwise if its 'email' then cart that was reminded by email will be
    | deleted.
    |
    | You may also specify whether to delete the old carts that cannot be 
    | associated to any user.
    |
    */

    'cart' => [
        'abandoned' => [
            'notifications'       => false,
            'first_notification'  => 60, // 1 hour
            'second_notification' => 24 * 60, // 1 day
            'third_notification'  => 3 * 24 * 60, // 3 days

            'merge_abandoned' => 'cookie', // email, null

            'delete'        => false,
            'delete_before' => '90 days'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | ACS
    |--------------------------------------------------------------------------
    |
    | Here you may specify the options for acs courier.
    | Billing codes must have the country code in upper case as the key
    | and the billing code itself as the value e.g. ['GR' => 'XN123456']
    |
    */

    'acs' => [
        'billing_codes' => [

        ]
    ],

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

    'variants' => [
        'sort' => [
            'available_first' => false
        ]
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
        'high_risk'         => false,
        'show_manufacturer' => true,
        'suggestions'       => 'similar',
        'image'             => [
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
    ],

    'analytics' => [
        'couriers' => true
    ]
];