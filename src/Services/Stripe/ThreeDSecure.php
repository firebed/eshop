<?php

namespace Eshop\Services\Stripe;

use Stripe\StripeObject;

class ThreeDSecure
{
    public bool    $authenticated;
    public string  $authentication_flow;
    public string  $result;
    public ?string $result_reason;
    public bool    $succeeded;
    public string  $version;

    public function __construct(StripeObject $dto)
    {
        $this->authenticated = $dto->authenticated;
        $this->authentication_flow = $dto->authentication_flow;
        $this->result = $dto->result;
        $this->result_reason = $dto->result_reason;
        $this->succeeded = $dto->succeeded;
        $this->version = $dto->version;
    }
}