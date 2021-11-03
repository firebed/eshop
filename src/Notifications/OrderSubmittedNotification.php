<?php

namespace Eshop\Notifications;

use Eshop\Models\Cart\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderSubmittedNotification extends Notification
{
    use Queueable;

    private Cart    $cart;
    private ?string $notesToCustomer;

    public function __construct(Cart $cart, ?string $notesToCustomer = null)
    {
        $this->cart = $cart;
        $this->notesToCustomer = $notesToCustomer;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        $this->cart->products->loadMissing('parent.translation', 'image', 'translation');

        $mail = new MailMessage();
        $mail->subject(__("Order Submitted Notification"));
        foreach (eshop('notifications.submitted.cc', []) as $cc) {
            $mail->cc($cc);
        }
        $mail->markdown('eshop::customer.emails.order.submitted', [
            'cart'            => $this->cart,
            'notesToCustomer' => $this->notesToCustomer
        ]);
        
        return $mail;
    }
}
