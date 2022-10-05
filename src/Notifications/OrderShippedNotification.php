<?php

namespace Eshop\Notifications;

use Eshop\Models\Cart\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class OrderShippedNotification extends Notification
{
    use Queueable;

    private Cart    $cart;
    private ?string $statusTemplate = null;
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
        
        $this->loadStatusTemplates();
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
        $mail->subject(__("Order Shipped Notification"));

        $mail->markdown('eshop::customer.emails.order.shipped', [
            'cart'            => $this->cart,
            'template'        => $this->statusTemplate,
            'notesToCustomer' => $this->notesToCustomer
        ]);

        return $mail;
    }

    private function loadStatusTemplates(): void
    {                
        if (!Lang::has('cart.status.templates.shipped')) {
            return;
        }

        $address = $this->cart->shippingAddress;
        if ($address === null) {
            return;
        }
        
        $days = $address->isLocalCountry() ? "1-3" : "5-10";

        $this->statusTemplate = __("cart.status.templates.shipped", [
            'customer' => $address->fullname,
            'days'     => $days
        ]);
    }
}