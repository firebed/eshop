<?php

namespace Eshop\Services\Skroutz\Listeners;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Product\Channel;
use Eshop\Services\Skroutz\Events\SkroutzPayoutReceived;
use Eshop\Services\Skroutz\Skroutz;
use Exception;

class HandleSkroutzPayouts
{
    private Skroutz $service;

    public function __construct(Skroutz $service)
    {
        $this->service = $service;
    }

    /**
     * @throws Exception
     */
    public function handle(SkroutzPayoutReceived $event): void
    {
        $cartsResolver = fn($references) => [
            'reference_id',
            Cart::query()
                ->select('id', 'reference_id', 'total')
                ->whereIn('reference_id', $references)
                ->with('shippingAddress', 'payment')
                ->get()
                ->keyBy('reference_id')
        ];

        $skroutz = Channel::firstWhere('name', 'Skroutz');

        $this->service->payouts()->processMessage($event->messageId, $skroutz, $cartsResolver);
    }
}
