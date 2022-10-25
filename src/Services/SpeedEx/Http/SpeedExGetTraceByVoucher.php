<?php

namespace Eshop\Services\SpeedEx\Http;

use Carbon\Carbon;
use Eshop\Services\SpeedEx\Exceptions\SpeedExException;
use Illuminate\Support\Collection;

class SpeedExGetTraceByVoucher extends SpeedExRequest
{
    protected string $action = 'GetTraceByVoucher';

    /**
     * @throws SpeedExException
     */
    public function handle(string $voucher): Collection
    {
        $response = $this->request([
            'VoucherID' => $voucher,
        ]);
        
        return collect($response->checkpoints->Checkpoint ?? [])
            ->map(fn($checkpoint) => (array)$checkpoint)
            ->sortByDesc('CheckpointDate')
            ->map(function ($checkpoint) {
                $city = str($checkpoint['Branch'])->after('-');
                $date = Carbon::parse($checkpoint['CheckpointDate']);
                return [
                    'title'       => str($checkpoint['StatusDesc']),
                    'description' => $city . ', ' . $date->format('d/m/Y στις H:i')
                ];
            });
    }
}