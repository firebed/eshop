<?php

namespace Eshop\Providers;

use Eshop\Events\CartStatusChanged;
use Eshop\Listeners\CreateLoginRecord;
use Eshop\Listeners\LogCartEvent;
use Eshop\Listeners\MergeCustomerCarts;
use Eshop\Listeners\SendCartStatusChangedNotification;
use Eshop\Services\Acs\Events\AcsPayoutReceived;
use Eshop\Services\Acs\Listeners\HandleAcsPayouts;
use Eshop\Services\CourierCenter\Events\CourierCenterPayoutReceived;
use Eshop\Services\CourierCenter\Listeners\HandleCourierCenterPayouts;
use Eshop\Services\GenikiTaxydromiki\Events\GenikiTaxydromikiPayoutReceived;
use Eshop\Services\GenikiTaxydromiki\Listeners\HandleGenikiTaxydromikiPayouts;
use Eshop\Services\Skroutz\Events\SkroutzPayoutReceived;
use Eshop\Services\Skroutz\Listeners\HandleSkroutzPayouts;
use Eshop\Services\SpeedEx\Events\SpeedExPayoutReceived;
use Eshop\Services\SpeedEx\Listeners\HandleSpeedExPayouts;
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
            HandleCourierCenterPayouts::class
        ],

        SkroutzPayoutReceived::class => [
            HandleSkroutzPayouts::class
        ],

        AcsPayoutReceived::class => [
            HandleAcsPayouts::class
        ],

        SpeedExPayoutReceived::class => [
            HandleSpeedExPayouts::class
        ],

        GenikiTaxydromikiPayoutReceived::class => [
            HandleGenikiTaxydromikiPayouts::class
        ]
    ];
}