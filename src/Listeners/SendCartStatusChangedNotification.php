<?php

namespace Eshop\Listeners;

use Eshop\Events\CartStatusChanged;
use Eshop\Models\Cart\CartStatus;
use Eshop\Notifications\OrderCancelledNotification;
use Eshop\Notifications\OrderHeldNotification;
use Eshop\Notifications\OrderRejectedNotification;
use Eshop\Notifications\OrderShippedNotification;
use Eshop\Notifications\OrderSubmittedNotification;
use Illuminate\Support\Facades\Notification;

class SendCartStatusChangedNotification
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
        if (empty($cart->email)) {
            return;
        }

        $status = $event->status;
        $notes = $event->notesToCustomer;

        switch ($status->name) {
            case CartStatus::SUBMITTED:
                Notification::route('mail', $cart->email)->notify(new OrderSubmittedNotification($cart, $notes));
                break;
            case CartStatus::SHIPPED:
                Notification::route('mail', $cart->email)->notify(new OrderShippedNotification($cart, $notes));
                break;
            case CartStatus::HELD:
                Notification::route('mail', $cart->email)->notify(new OrderHeldNotification($cart, $notes));
                break;
            case CartStatus::CANCELLED:
                Notification::route('mail', $cart->email)->notify(new OrderCancelledNotification($cart, $notes));
                break;
            case CartStatus::REJECTED:
                Notification::route('mail', $cart->email)->notify(new OrderRejectedNotification($cart, $notes));
                break;
        }
    }
}
