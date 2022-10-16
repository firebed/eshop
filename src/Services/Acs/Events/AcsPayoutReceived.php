<?php

namespace Eshop\Services\Acs\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class AcsPayoutReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string     $reference_id;
    public Collection $payouts;

    public function __construct(string $reference_id, Collection $payouts)
    {
        $this->reference_id = $reference_id;
        $this->payouts = $payouts;
    }
}