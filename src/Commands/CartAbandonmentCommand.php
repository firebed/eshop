<?php

namespace Eshop\Commands;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartEvent;
use Eshop\Notifications\OrderAbandonedNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class CartAbandonmentCommand extends Command
{
    protected $signature = 'cart:reminder';

    protected $description = 'Send notifications for the abandoned carts.';

    private Carbon $now;

    public function handle(): int
    {
        $this->now = now();

        $lastEmailNotification = eshop('cart.abandoned.third_notification') + 1;
        $from = now()->subMinutes($lastEmailNotification)->startOfMinute();

        $carts = Cart::query()
            ->whereNotNull('email')
            ->whereNull('submitted_at')
            ->whereBetween('created_at', [$from, $this->now])
            ->whereHas('products')
            ->with('events', 'shippingMethod', 'paymentMethod', 'products')
            ->get();

        foreach ($carts as $cart) {
            $this->processCart($cart);
        }

        return 0;
    }

    private function processCart($cart): void
    {
        if ($this->thirdNotificationSent($cart)) {
            // Suppress further notifications
            return;
        }

        $diff = $cart->created_at->diffInMinutes($this->now);

        if ($this->secondNotificationSent($cart)) {
            $thirdNotificationMinutes = eshop('cart.abandoned.third_notification');

            if ($diff >= $thirdNotificationMinutes) {
                $this->sendThirdNotification($cart);
            }

            return;
        }

        if ($this->firstNotificationSent($cart)) {
            $secondNotificationMinutes = eshop('cart.abandoned.second_notification');

            if ($diff >= $secondNotificationMinutes) {
                $this->sendSecondNotification($cart);
            }

            return;
        }

        $firstNotificationMinutes = eshop('cart.abandoned.first_notification');

        if ($diff >= $firstNotificationMinutes) {
            $this->sendFirstNotification($cart);
        }
    }

    private function firstNotificationSent($cart): bool
    {
        return $cart->events->contains('action', CartEvent::ABANDONMENT_EMAIL_1);
    }

    private function secondNotificationSent($cart): bool
    {
        return $cart->events->contains('action', CartEvent::ABANDONMENT_EMAIL_2);
    }

    private function thirdNotificationSent($cart): bool
    {
        return $cart->events->contains('action', CartEvent::ABANDONMENT_EMAIL_3);
    }

    private function sendFirstNotification($cart)
    {
        $event = CartEvent::info($cart->id, CartEvent::ABANDONMENT_EMAIL_1);

        Notification::route('mail', $cart->email)->notify(new OrderAbandonedNotification($cart, __("eshop::cart.events.first_abandonment_subject"), $event));
    }

    private function sendSecondNotification($cart)
    {
        $event = CartEvent::info($cart->id, CartEvent::ABANDONMENT_EMAIL_2);

        Notification::route('mail', $cart->email)->notify(new OrderAbandonedNotification($cart, __("eshop::cart.events.second_abandonment_subject"), $event));
    }

    private function sendThirdNotification($cart)
    {
        $event = CartEvent::info($cart->id, CartEvent::ABANDONMENT_EMAIL_3);

        Notification::route('mail', $cart->email)->notify(new OrderAbandonedNotification($cart, __("eshop::cart.events.third_abandonment_subject"), $event));
    }
}
