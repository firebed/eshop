<?php

namespace Eshop\Services\CourierCenter\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CourierCenterPayoutReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $messageId;

    public function __construct(string $messageId)
    {
        $this->messageId = $messageId;
    }
}