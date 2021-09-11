<?php

namespace Eshop\Services\Stripe;

use Stripe\StripeObject;

class CardChecks
{
    public string $address_line1_check;
    public string $address_postal_code_check;
    public string $cvc_check;

    public function __construct(StripeObject $dto)
    {
        $this->address_line1_check = $dto->address_line1_check;
        $this->address_postal_code_check = $dto->address_postal_code_check;
        $this->cvc_check = $dto->cvc_check;
    }
}