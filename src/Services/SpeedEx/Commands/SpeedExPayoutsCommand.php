<?php

namespace Eshop\Services\SpeedEx\Commands;

use Carbon\Carbon;
use Eshop\Services\Payout\PayoutsCommand;
use Eshop\Services\SpeedEx\Events\SpeedExPayoutReceived;
use Eshop\Services\SpeedEx\Http\SpeedExGetPayouts;

class SpeedExPayoutsCommand extends PayoutsCommand
{
    protected $signature = 'speedex:payouts {--on=}';

    protected $description = 'Synchronize payments from SpeedEx courier.';

    public function handle(SpeedExGetPayouts $speedEx): void
    {
        $on = filled($this->option('on')) ? Carbon::parse($this->option('on')) : today();
        
        $payouts = collect($speedEx->handle($on))
            ->keyBy('ConsignmentNumber')
            ->sortKeys();
        
        $reference_id = md5(serialize($payouts->keys()->toArray()));
        if ($payouts->isEmpty() || !$this->isNew($reference_id)) {
            return;
        }

        event(new SpeedExPayoutReceived($reference_id, $payouts));
    }
}