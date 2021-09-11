<?php

namespace Eshop\Services\Stripe;

use Stripe\PaymentIntent;

class StripeCard
{
    public string       $brand;
    public CardChecks   $checks;
    public string       $country;
    public int          $exp_month;
    public int          $exp_year;
    public string       $fingerprint;
    public string       $funding;
    public ?string      $installments;
    public string       $last4;
    public string       $network;
    public ThreeDSecure $three_d_secure;

    public function __construct(PaymentIntent $intent)
    {
        $card = $intent->charges->data[0]['payment_method_details']->card;

        $this->brand = $card->brand;
        $this->checks = new CardChecks($card->checks);
        $this->country = $card->country;
        $this->exp_month = $card->exp_month;
        $this->exp_year = $card->exp_year;
        $this->fingerprint = $card->fingerprint;
        $this->funding = $card->funding;
        $this->installments = $card->installments;
        $this->last4 = $card->last4;
        $this->network = $card->network;
        $this->three_d_secure = new ThreeDSecure($card->three_d_secure);
    }
}