<?php

namespace Eshop\Services\Acs\Listeners;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Location\ShippingMethod;
use Eshop\Services\Acs\Acs;
use Eshop\Services\Acs\Events\AcsPayoutReceived;

class HandleAcsPayouts
{
    private Acs $acs;

    public function __construct(Acs $acs)
    {
        $this->acs = $acs;
    }

    public function handle(AcsPayoutReceived $event): void
    {
        $acs = ShippingMethod::firstWhere('name', 'ACS Courier');

        $payouts = $event->payouts->map(fn($payout) => [
            'customer_name' => $payout['Parcel_Receiver'],
            'fees'          => 0,
            'total'         => $payout['Parcel_COD_Amount'],
        ]);

        $cartsResolver = fn($references) => [
            'voucher',
            Cart::query()
                ->select(['id', 'voucher', 'total'])
                ->where('shipping_method_id', $acs->id)
                ->whereIn('voucher', $references)
                ->with('shippingAddress', 'payment')
                ->get()
                ->keyBy('voucher')
        ];

        $this->acs->payouts()->processPayouts($event->reference_id, $payouts, $acs, $cartsResolver, now());
    }
}
