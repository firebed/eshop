<?php

namespace Eshop\Services\CourierCenter\Listeners;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Location\ShippingMethod;
use Eshop\Services\CourierCenter\CourierCenterService;
use Eshop\Services\CourierCenter\Events\CourierCenterPayoutReceived;
use Eshop\Services\Imap\Exceptions\ImapException;

class HandleCourierCenterPayouts
{
    private CourierCenterService $service;

    public function __construct(CourierCenterService $service)
    {
        $this->service = $service;
    }

    /**
     * @throws ImapException
     */
    public function handle(CourierCenterPayoutReceived $event): void
    {
        $courierCenter = ShippingMethod::firstWhere('name', 'Courier Center');

        $cartsResolver = fn($references) => [
            'voucher',
            Cart::query()
                ->select('id', 'voucher', 'total')
                ->where('shipping_method_id', $courierCenter->id)
                ->whereIn('voucher', $references)
                ->with('shippingAddress', 'payment')
                ->get()
                ->keyBy('voucher')
        ];

        $this->service->payouts()->processMessage($event->messageId, $courierCenter, $cartsResolver);
    }
}
