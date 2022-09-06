<?php

namespace Eshop\Listeners;

use Eshop\Events\CartStatusChanged;
use Eshop\Models\Cart\CartEvent;
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
        if (!$event->notifyCustomer) {
            return;
        }
        
        $cart = $event->cart;
        if (empty($cart->email)) {
            return;
        }

        $status = $event->status;
        $notes = $event->notesToCustomer;
        
        switch ($status->name) {
            case CartStatus::SUBMITTED:
                Notification::route('mail', $cart->email)->notify(new OrderSubmittedNotification($cart, $notes));
                CartEvent::info($cart->id, CartEvent::ORDER_SUBMITTED_EMAIL);
                break;
            case CartStatus::APPROVED:
                
                CartEvent::info($cart->id, CartEvent::ORDER_APPROVED_EMAIL);
                break;
            case CartStatus::COMPLETED:

                CartEvent::info($cart->id, CartEvent::ORDER_COMPLETED_EMAIL);
                break;
            case CartStatus::SHIPPED:
                Notification::route('mail', $cart->email)->notify(new OrderShippedNotification($cart, $notes));
                CartEvent::info($cart->id, CartEvent::ORDER_SHIPPED_EMAIL);
                break;
            case CartStatus::ON_HOLD:
                Notification::route('mail', $cart->email)->notify(new OrderHeldNotification($cart, $notes));
                CartEvent::info($cart->id, CartEvent::ORDER_HELD_EMAIL);
                break;
            case CartStatus::CANCELLED:
                Notification::route('mail', $cart->email)->notify(new OrderCancelledNotification($cart, $notes));
                CartEvent::info($cart->id, CartEvent::ORDER_CANCELLED_EMAIL);
                break;
            case CartStatus::REJECTED:
                Notification::route('mail', $cart->email)->notify(new OrderRejectedNotification($cart, $notes));
                CartEvent::info($cart->id, CartEvent::ORDER_REJECTED_EMAIL);
                break;
            case CartStatus::RETURNED:
                
                CartEvent::info($cart->id, CartEvent::ORDER_RETURNED_EMAIL);
                break;
        }
    }
}
