<?php

namespace Eshop\Services\Skroutz\Commands;

use Carbon\Carbon;
use Eshop\Services\Imap\ImapService;
use Eshop\Services\Skroutz\Events\SkroutzPayoutReceived;
use Exception;
use Illuminate\Console\Command;
use Throwable;

class SkroutzPayoutsCommand extends Command
{
    protected $signature = 'skroutz:payouts {--on=}';

    protected $description = 'Reads the mail inbox for a given date and address';

    /**
     * @throws Exception
     */
    public function handle(ImapService $imap): int
    {
        $on = filled($this->option('on')) ? Carbon::parse($this->option('on')) : today();

        try {
            $messages = $imap->preview($on, null, 'noreply@skroutz.gr')->keys();

            foreach ($messages as $messageId) {
                event(new SkroutzPayoutReceived($messageId));
            }
        } catch (Throwable $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}