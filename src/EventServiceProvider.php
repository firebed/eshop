<?php

namespace Eshop;

use Eshop\Events\CartStatusChanged;
use Eshop\Listeners\MergeCustomerCarts;
use Eshop\Listeners\SendCartStatusChangedNotification;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CartStatusChanged::class => [
            SendCartStatusChangedNotification::class
        ],

        Login::class => [
            MergeCustomerCarts::class
        ]
    ];
}