<?php

namespace Eshop\Services\Payout;

use Illuminate\Support\Collection;
use Webklex\PHPIMAP\Attachment;

interface PayoutReader
{
    public function payouts(): Payout;
    
    public function resolvePayoutsAttachment(Attachment $attachment): Collection;
}