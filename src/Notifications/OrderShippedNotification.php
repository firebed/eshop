<?php

namespace Eshop\Notifications;

use Eshop\Mail\OrderShippedMail;
use Eshop\Models\Cart\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
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
     * @return MailMessage
     */
    public function toMail(): MailMessage
    {
        $this->cart->products->loadMissing('parent.translation', 'image', 'translation');

        $mail = new MailMessage();
        $mail->subject(__("Order Shipped Notification"));
        foreach (eshop('notifications.shipped.cc', []) as $cc) {
            $mail->cc($cc);
        }
        $mail->markdown('eshop::customer.emails.order.shipped', [
            'cart'            => $this->cart,
            'notesToCustomer' => $this->notesToCustomer
        ]);
        
        return $mail;
    }
}
