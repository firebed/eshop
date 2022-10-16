<?php

namespace Eshop\Services\GenikiTaxydromiki\Commands;

use Carbon\Carbon;
use Eshop\Services\GenikiTaxydromiki\Events\GenikiTaxydromikiPayoutReceived;
use Eshop\Services\GenikiTaxydromiki\GenikiTaxydromiki;
use Eshop\Services\Payout\PayoutsCommand;
use Throwable;

class GenikiTaxydromikiPayoutsCommand extends PayoutsCommand
{
    protected $signature = 'geniki:payouts {--on=}';

    protected $description = 'Reads the mail inbox for a given date and address';

    public function handle(GenikiTaxydromiki $courier): int
    {
        $on = filled($this->option('on')) ? Carbon::parse($this->option('on')) : today();

        try {
            $messages = $courier->payouts()->previewMessages($on)->keys();

            foreach ($messages as $messageId) {
                if (!$this->isNew($messageId)) {
                    continue;
                }

                event(new GenikiTaxydromikiPayoutReceived($messageId));
            }
        } catch (Throwable $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}
