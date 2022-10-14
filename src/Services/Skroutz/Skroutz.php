<?php

namespace Eshop\Services\Skroutz;

use Eshop\Services\Payout\HasPayouts;
use Eshop\Services\Skroutz\Actions\AcceptOrder;
use Eshop\Services\Skroutz\Actions\CreateOrder;
use Eshop\Services\Skroutz\Actions\RejectOrder;
use Eshop\Services\Skroutz\Actions\RetrieveOrder;
use Eshop\Services\Skroutz\Actions\UpdateOrder;
use Eshop\Services\Skroutz\Actions\UploadInvoice;
use Eshop\Services\Skroutz\Exceptions\SkroutzException;

class Skroutz
{
    use HasPayouts;
    
    private const PAYOUTS_ADDRESS = 'noreply@skroutz.gr';
    
    public static function handleWebhookRequest($event): void
    {
        match ($event['event_type']) {
            'new_order'     => (new CreateOrder())->handle($event),
            'order_updated' => (new UpdateOrder())->handle($event)
        };
    }

    /**
     * @throws SkroutzException
     */
    public static function retrieveOrder(string $skroutzOrderId)
    {
        return (new RetrieveOrder())->handle($skroutzOrderId);
    }

    /**
     * @throws SkroutzException
     *
     * @see AcceptOrder::handle()
     */
    public static function acceptOrder(string $skroutzOrderId, string $pickup_location, int $pickup_window, int $number_of_parcels = 1): bool
    {
        return (new AcceptOrder())->handle($skroutzOrderId, $pickup_location, $pickup_window, $number_of_parcels);
    }

    /**
     * @throws SkroutzException
     *
     * @see RejectOrder::handle()
     */
    public static function rejectOrder(string $skroutzOrderId, array $line_items = [], string $rejection_reason_other = null): bool
    {
        return (new RejectOrder())->handle($skroutzOrderId, $line_items, $rejection_reason_other);
    }

    /**
     * @throws SkroutzException
     *
     * @see UploadInvoice::handle()
     */
    public static function uploadInvoice(string $skroutzOrderId, string $invoice): bool
    {
        return (new UploadInvoice())->handle($skroutzOrderId, $invoice);
    }
}