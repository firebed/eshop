<?php

return [
    'invoicing' => env('INVOICING', true),

    'locales' => [],

    'default_locale' => env('COUNTRY_LOCALE', 'en'),
    'country'        => env('COUNTRY', 'us'),
    'currency'       => env('CURRENCY', 'USD'),

    'google-analytics-id' => env('GOOGLE_ANALYTICS_ID'),
];