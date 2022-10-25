<?php

namespace Eshop\Services\CourierCenter\Commands;

use Carbon\Carbon;
use Eshop\Services\CourierCenter\CourierCenter;
use Eshop\Services\CourierCenter\Events\CourierCenterPayoutReceived;
use Eshop\Services\Payout\PayoutsCommand;
use Throwable;

class CourierCenterPayoutsCommand extends PayoutsCommand
{
    protected $signature = 'courier-center:payouts {--on=}';

    protected $description = 'Reads the mail inbox for a given date and address';

    public function handle(CourierCenter $service): int
    {
        $on = filled($this->option('on')) ? Carbon::parse($this->option('on')) : today();

        try {
            $messages = $service->payouts()->previewMessages($on)->keys();

            foreach ($messages as $messageId) {
                if (!$this->isNew($messageId)) {
                    continue;
                }

                event(new CourierCenterPayoutReceived($messageId));
            }
        } catch (Throwable $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}
