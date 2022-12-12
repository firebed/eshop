<?php

namespace Eshop\Services\Skroutz\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SkroutzPayoutReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $messageId;

    public function __construct(string $messageId)
    {
        $this->messageId = $messageId;
    }
}