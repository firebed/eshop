<?php

namespace Eshop\Services\SpeedEx;

use Eshop\Services\Payout\HasPayouts;
use Eshop\Services\Payout\PayoutReader;
use Illuminate\Support\Collection;
use Webklex\PHPIMAP\Attachment;

class SpeedEx implements PayoutReader
{
    use HasPayouts;

    private const PAYOUTS_ADDRESS = null;
    
    public function validatePayoutAttachment(Attachment $attachment): bool
    {
        return false;
    }

    public function handlePayoutsAttachment(string $filename): Collection
    {
        return collect();
    }
}