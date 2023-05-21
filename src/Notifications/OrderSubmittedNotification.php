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

        //$address = $this->cart->shippingAddress;
        //if (isset($address->country->code)) {
        //    $locale = in_array($address->country->code, ['GR', 'CY']) ? 'el' : 'en';
        //    app()->setLocale($locale);
        //}
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        $this->cart->products->loadMissing('parent.translations', 'image', 'translations', 'variantOptions.translations');

        $mail = new MailMessage();
        $mail->subject(__("Order Submitted Notification"));
        $this->addCC($mail);        
        
        $mail->markdown('eshop::customer.emails.order.submitted', [
            'cart'            => $this->cart,
            'notesToCustomer' => $this->notesToCustomer
        ]);
        
        return $mail;
    }

    private function addCC(MailMessage $mail): void
    {
        $recipients = array_filter(eshop('notifications.submitted.cc', []));
        foreach ($recipients as $recipient) {
            $mail->cc($recipient);
        }
    }
}
