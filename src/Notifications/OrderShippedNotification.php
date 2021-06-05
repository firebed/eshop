<?php

namespace Eshop\Notifications;

use Eshop\Mail\OrderShippedMail;
use Eshop\Models\Cart\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderShippedNotification extends Notification
{
    use Queueable;

    private Cart    $cart;
    private ?string $notesToCustomer;

    /**
     * Create a new notification instance.
     *
     * @param Cart        $cart
     * @param string|null $notesToCustomer
     */
    public function __construct(Cart $cart, ?string $notesToCustomer = NULL)
    {
        $this->cart = $cart;
        $this->notesToCustomer = $notesToCustomer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return OrderShippedMail
     */
    public function toMail(): OrderShippedMail
    {
        return new OrderShippedMail($this->cart, $this->notesToCustomer);
    }
}
