<?php

namespace Eshop\Notifications;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderAbandonedNotification extends Notification
{
    use Queueable;

    private readonly Cart   $cart;
    private readonly string $subject;
    private CartEvent       $event;

    public function __construct(Cart $cart, string $subject, CartEvent $event)
    {
        $this->subject = $subject;
        $this->cart = $cart;
        $this->event = $event;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        $this->cart->products->loadMissing('parent.translations', 'image', 'translations', 'variantOptions.translations');

        $mail = new MailMessage();
        $mail->subject($this->subject);

        $mail->markdown('eshop::customer.emails.order.abandoned', [
            'cart'  => $this->cart,
            'event' => $this->event
        ]);

        return $mail;
    }
}
