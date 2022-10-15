<?php

namespace Eshop\Services\Skroutz\Commands;

use Eshop\Services\Payout\PayoutsCommand;
use Eshop\Services\Skroutz\Events\SkroutzPayoutReceived;
use Eshop\Services\Skroutz\Skroutz;
use Exception;
use Illuminate\Support\Carbon;
use Throwable;

class SkroutzPayoutsCommand extends PayoutsCommand
{
    protected $signature = 'payouts:skroutz {--on=}';

    protected $description = 'Reads the mail inbox for a given date and address';

    /**
     * @throws Exception
     */
    public function handle(Skroutz $service): int
    {
        $on = filled($this->option('on')) ? Carbon::parse($this->option('on')) : null;

        try {
            $messages = $service->payouts()->previewMessages($on);

            $this->info($messages->count() . ' messages');

            foreach ($messages as $messageId => $message) {
                if (!$this->isNew($messageId)) {
                    continue;
                }

                $payouts = $service->payouts()->find($messageId);

                if ($payouts->filter()->isNotEmpty()) {
                    foreach ($payouts as $payout) {
                        event(new SkroutzPayoutReceived($payout, $message['date']));

                        $this->info($payout->count() . " orders of total: " . format_currency($payout->sum('payoutTotal')));
                    }
                }
            }
        } catch (Throwable $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}