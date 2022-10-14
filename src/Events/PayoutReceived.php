<?php

namespace Eshop\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class PayoutReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int        $courierId;
    public Collection $payouts;
    public string     $from;
    public Carbon     $date;

    public function __construct(int $courierId, Collection $payouts, string $senderName, Carbon $date)
    {
        $this->courierId = $courierId;
        $this->payouts = $payouts;
        $this->from = $senderName;
        $this->date = $date;
    }
}