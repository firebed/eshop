<?php

namespace Eshop\Services\Payout;

use Illuminate\Support\Collection;
use Webklex\PHPIMAP\Attachment;

interface PayoutReader
{
    public function payouts(): Payout;

    public function validatePayoutAttachment(Attachment $attachment): bool;
    
    public function handlePayoutsAttachment(string $filename): Collection;
}