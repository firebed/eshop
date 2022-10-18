<?php

namespace Eshop\Services\Acs\Commands;

use Carbon\Carbon;
use Eshop\Services\Acs\Events\AcsPayoutReceived;
use Eshop\Services\Acs\Http\AcsPaymentsInfo;
use Eshop\Services\Payout\PayoutsCommand;

class AcsPayoutsCommand extends PayoutsCommand
{
    protected $signature = 'acs:payouts {--on=}';

    protected $description = 'Synchronize payments from ACS courier.';

    public function handle(AcsPaymentsInfo $acsPayments): void
    {
        $on = filled($this->option('on')) ? Carbon::parse($this->option('on')) : today();

        $payouts = collect($acsPayments->handle($on))
            ->keyBy('POD')
            ->sortKeys();

        $reference_id = md5(serialize($payouts->keys()->toArray()));
        if ($payouts->isEmpty() || !$this->isNew($reference_id)) {
            return;
        }

        event(new AcsPayoutReceived($reference_id, $payouts));
    }
}