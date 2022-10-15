<?php

namespace Eshop\Services\CourierCenter\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class CourierCenterPayoutReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Collection $payouts;
    public Carbon     $date;

    public function __construct(Collection $payouts, Carbon $date)
    {
        $this->payouts = $payouts;
        $this->date = $date;
    }
}