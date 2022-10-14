<?php

namespace Eshop\Services\CourierCenter;

use Eshop\Services\CourierCenter\Actions\ProcessPayoutAttachment;
use Eshop\Services\Payout\HasPayouts;
use Eshop\Services\Payout\PayoutReader;
use Illuminate\Support\Collection;
use Webklex\PHPIMAP\Attachment;

class CourierCenterService implements PayoutReader
{
    use HasPayouts;

    private const PAYOUTS_ADDRESS = "cod@courier.gr";

    public function resolvePayoutsAttachment(Attachment $attachment): Collection
    {
        return (new ProcessPayoutAttachment())->handle($attachment);
    }
}