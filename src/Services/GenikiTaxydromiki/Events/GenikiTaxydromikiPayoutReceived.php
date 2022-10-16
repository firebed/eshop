<?php

namespace Eshop\Services\GenikiTaxydromiki\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GenikiTaxydromikiPayoutReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $messageId;

    public function __construct(string $messageId)
    {
        $this->messageId = $messageId;
    }
}