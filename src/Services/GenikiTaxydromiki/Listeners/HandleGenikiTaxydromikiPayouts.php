<?php

namespace Eshop\Services\GenikiTaxydromiki\Listeners;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Location\ShippingMethod;
use Eshop\Services\GenikiTaxydromiki\Events\GenikiTaxydromikiPayoutReceived;
use Eshop\Services\GenikiTaxydromiki\GenikiTaxydromiki;
use Eshop\Services\Imap\Exceptions\ImapException;

class HandleGenikiTaxydromikiPayouts
{
    private GenikiTaxydromiki $courier;

    public function __construct(GenikiTaxydromiki $service)
    {
        $this->courier = $service;
    }

    /**
     * @throws ImapException
     */
    public function handle(GenikiTaxydromikiPayoutReceived $event): void
    {
        $geniki = ShippingMethod::firstWhere('name', 'Γενική ταχυδρομική');

        $cartsResolver = fn($references) => [
            'voucher',
            Cart::query()
                ->select('id', 'voucher', 'total')
                ->where('shipping_method_id', $geniki->id)
                ->whereIn('voucher', $references)
                ->with('shippingAddress', 'payment')
                ->get()
                ->keyBy('voucher')
        ];

        $this->courier->payouts()->processMessage($event->messageId, $geniki, $cartsResolver);
    }
}
