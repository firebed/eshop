<?php

namespace Eshop\Providers;

use Eshop\Events\CartStatusChanged;
use Eshop\Listeners\CreateLoginRecord;
use Eshop\Listeners\LogCartEvent;
use Eshop\Listeners\MergeCustomerCarts;
use Eshop\Listeners\SendCartStatusChangedNotification;
use Eshop\Services\CourierCenter\Events\CourierCenterPayoutReceived;
use Eshop\Services\CourierCenter\Listeners\CourierCenterProcessPayouts;
use Eshop\Services\Skroutz\Events\SkroutzPayoutReceived;
use Eshop\Services\Skroutz\Listeners\SkroutzProcessPayouts;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CartStatusChanged::class => [
            LogCartEvent::class,
            SendCartStatusChangedNotification::class
        ],

        Login::class => [
            MergeCustomerCarts::class,
            CreateLoginRecord::class
        ],

        CourierCenterPayoutReceived::class => [
            CourierCenterProcessPayouts::class
        ],
        
        SkroutzPayoutReceived::class => [
            SkroutzProcessPayouts::class
        ]
    ];
}