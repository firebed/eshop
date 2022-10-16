<?php

namespace Eshop\Services\Skroutz\Commands;

use Carbon\Carbon;
use Eshop\Services\Payout\PayoutsCommand;
use Eshop\Services\Skroutz\Events\SkroutzPayoutReceived;
use Eshop\Services\Skroutz\Skroutz;
use Exception;
use Throwable;

class SkroutzPayoutsCommand extends PayoutsCommand
{
    protected $signature = 'skroutz:payouts {--on=}';

    protected $description = 'Reads the mail inbox for a given date and address';

    /**
     * @throws Exception
     */
    public function handle(Skroutz $service): int
    {
        $on = filled($this->option('on')) ? Carbon::parse($this->option('on')) : today();

        try {
            $messages = $service->payouts()->previewMessages($on)->keys();

            foreach ($messages as $messageId) {
                if (!$this->isNew($messageId)) {
                    continue;
                }
                
                event(new SkroutzPayoutReceived($messageId));
            }
        } catch (Throwable $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}