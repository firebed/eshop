<?php

namespace Eshop\Services\Payout;

trait HasPayouts
{
    public function payouts(): Payout
    {
        return new Payout(self::PAYOUTS_ADDRESS, $this);
    }
}