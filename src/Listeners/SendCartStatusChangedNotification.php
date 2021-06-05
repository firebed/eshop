<?php

namespace Eshop\Listeners;

use Eshop\Events\CartStatusChanged;
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
        $status = $event->current_status;
        $contact = $event->cart->contact()->sole();
        $notes = $event->notesToCustomer;

        switch ($status->name) {
            case "Submitted":
                Notification::route('mail', $contact->email)->notify(new OrderSubmittedNotification($cart, $notes));
                break;
            case "Shipped":
                Notification::route('mail', $contact->email)->notify(new OrderShippedNotification($cart, $notes));
                break;
            case "Held":
                Notification::route('mail', $contact->email)->notify(new OrderHeldNotification($cart, $notes));
                break;
            case "Cancelled":
                Notification::route('mail', $contact->email)->notify(new OrderCancelledNotification($cart, $notes));
                break;
            case "Rejected":
                Notification::route('mail', $contact->email)->notify(new OrderRejectedNotification($cart, $notes));
                break;
        }
    }
}
