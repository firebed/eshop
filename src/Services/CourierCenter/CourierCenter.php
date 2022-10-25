<?php

namespace Eshop\Services\CourierCenter;

use Eshop\Services\CourierCenter\Imports\CourierCenterPayoutsImport;
use Eshop\Services\Payout\HasPayouts;
use Eshop\Services\Payout\PayoutReader;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Webklex\PHPIMAP\Attachment;

class CourierCenter implements PayoutReader
{
    use HasPayouts;

    private const PAYOUTS_ADDRESS = "cod@courier.gr";

    public function validatePayoutAttachment(Attachment $attachment): bool
    {
        return $attachment->getMimeType() === "application/vnd.ms-excel";
    }

    public function handlePayoutsAttachment(string $filename): Collection
    {
        $path = $this->payouts()->disk()->path($filename);
        
        return Excel::toCollection(new CourierCenterPayoutsImport(), $path)->first()->mapWithKeys(fn($v) => $v);
    }
}