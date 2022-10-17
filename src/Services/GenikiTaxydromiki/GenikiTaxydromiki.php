<?php

namespace Eshop\Services\GenikiTaxydromiki;

use Eshop\Services\GenikiTaxydromiki\Imports\GenikiTaxydromikiImport;
use Eshop\Services\Payout\HasPayouts;
use Eshop\Services\Payout\PayoutReader;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Webklex\PHPIMAP\Attachment;

class GenikiTaxydromiki implements PayoutReader
{
    use HasPayouts;

    private const PAYOUTS_ADDRESS = "cod@taxydromiki.gr";

    public function validatePayoutAttachment(Attachment $attachment): bool
    {
        return $attachment->getMimeType() === "text/plain";
    }

    public function handlePayoutsAttachment(string $filename): Collection
    {
        $path = $this->payouts()->disk()->path($filename);

        return Excel::toCollection(new GenikiTaxydromikiImport(), $path)->first()->mapWithKeys(fn($v) => $v);
    }
}