<?php

namespace Eshop\Services\SpeedEx\Listeners;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Location\ShippingMethod;
use Eshop\Services\SpeedEx\Events\SpeedExPayoutReceived;
use Eshop\Services\SpeedEx\SpeedEx;

class HandleSpeedExPayouts
{
    private SpeedEx $speedEx;

    public function __construct(SpeedEx $speedEx)
    {
        $this->speedEx = $speedEx;
    }

    public function handle(SpeedExPayoutReceived $event): void
    {
        $method = ShippingMethod::firstWhere('name', 'SpeedEx');

        $payouts = $event->payouts->map(fn($payout) => [
            'customer_name' => null,
            'fees'          => 0,
            'total'         => $payout->Amount,
        ]);

        $cartsResolver = fn($references) => [
            'voucher',
            Cart::query()
                ->select(['id', 'voucher', 'total'])
                ->where('shipping_method_id', $method->id)
                ->whereIn('voucher', $references)
                ->with('shippingAddress', 'payment')
                ->get()
                ->keyBy('voucher')
        ];

        $this->speedEx->payouts()->processPayouts($event->reference_id, $payouts, $method, $cartsResolver, now());
    }
}
