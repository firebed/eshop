<?php

namespace Eshop\Notifications;

use Eshop\Models\Cart\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCancelledNotification extends Notification
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
    public function __construct(Cart $cart, ?string $notesToCustomer = null)
    {
        $this->cart = $cart;
        $this->notesToCustomer = $notesToCustomer;

        $address = $this->cart->shippingAddress;
        if (isset($address->country->code)) {
            $locale = in_array($address->country->code, ['GR', 'CY']) ? 'el' : 'en';
            app()->setLocale($locale);
        }
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
     * @return MailMessage
     */
    public function toMail(): MailMessage
    {
        $this->cart->products->loadMissing('parent.translations', 'image', 'translations', 'variantOptions.translations');

        $mail = new MailMessage();
        $mail->subject(__("Order Cancelled Notification"));

        $mail->markdown('eshop::customer.emails.order.cancelled', [
            'cart'            => $this->cart,
            'notesToCustomer' => $this->notesToCustomer
        ]);

        return $mail;
    }
}
