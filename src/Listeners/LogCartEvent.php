<?php

namespace Eshop\Listeners;

use Eshop\Events\CartStatusChanged;
use Eshop\Models\Cart\CartEvent;
use Eshop\Models\Cart\CartStatus;

class LogCartEvent
{
    /**
     * Handle the event.
     *
     * @param CartStatusChanged $event
     * @return void
     */
    public function handle(CartStatusChanged $event): void
    {
        $cart = $event->cart;
        $status = $event->status;
        $notes = $event->notesToCustomer;

        switch ($status->name) {
            case CartStatus::SUBMITTED:
                CartEvent::success($cart->id, CartEvent::ORDER_SUBMITTED, $notes);
                break;
            case CartStatus::APPROVED:
                CartEvent::info($cart->id, CartEvent::ORDER_APPROVED, $notes);
                break;
            case CartStatus::COMPLETED:
                CartEvent::info($cart->id, CartEvent::ORDER_COMPLETED, $notes);
                break;
            case CartStatus::SHIPPED:
                CartEvent::info($cart->id, CartEvent::ORDER_SHIPPED, $notes);
                break;
            case CartStatus::ON_HOLD:
                CartEvent::warning($cart->id, CartEvent::ORDER_HELD, $notes);
                break;
            case CartStatus::CANCELLED:
                CartEvent::error($cart->id, CartEvent::ORDER_CANCELLED, $notes);
                break;
            case CartStatus::REJECTED:
                CartEvent::error($cart->id, CartEvent::ORDER_REJECTED, $notes);
                break;
            case CartStatus::RETURNED:
                CartEvent::error($cart->id, CartEvent::ORDER_RETURNED, $notes);
                break;
        }
    }
}
