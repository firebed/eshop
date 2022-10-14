<?php

namespace Eshop\Services\CourierCenter\Actions;

use Eshop\Services\CourierCenter\Imports\CourierCenterPayoutsImport;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Webklex\PHPIMAP\Attachment;

class ProcessPayoutAttachment
{
    public function handle(Attachment $attachment): Collection
    {
        if (!$this->validate($attachment)) {
            return collect();
        }
        
        $path = $this->writeFileToDisk($attachment);

        $payouts = Excel::toCollection(new CourierCenterPayoutsImport(), $this->disk()->path($path))
            ->first()
            ->mapWithKeys(fn($v) => $v);

        $this->disk()->delete($path);

        return $payouts;
    }

    private function writeFileToDisk(Attachment $attachment): string
    {
        $filename = Str::random(40) . '.' . $attachment->getExtension();
        $path = "imap/$filename";
        $this->disk()->put($path, $attachment->getContent());
        
        return $path;
    }

    private function validate(Attachment $attachment): bool
    {
        return $attachment->getMimeType() === "application/vnd.ms-excel";
    }

    private function disk(): Filesystem
    {
        return Storage::disk('local');
    }
}