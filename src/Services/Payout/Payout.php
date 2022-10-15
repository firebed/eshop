<?php

namespace Eshop\Services\Payout;

use Eshop\Services\Imap\Exceptions\ImapException;
use Eshop\Services\Imap\ImapService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Throwable;
use Webklex\PHPIMAP\Message;
use Webklex\PHPIMAP\Support\MessageCollection;

class Payout
{
    private ImapService $imap;

    public function __construct(readonly private string $payoutAddress, readonly PayoutReader $reader)
    {
        $this->imap = new ImapService();
    }

    /**
     * @throws ImapException
     */
    public function on(Carbon $on): Collection
    {
        return $this->query($on);
    }

    /**
     * @throws ImapException
     */
    public function since(Carbon $since): Collection
    {
        return $this->query(since: $since);
    }

    /**
     * @throws ImapException
     */
    public function all(): Collection
    {
        return $this->query();
    }

    /**
     * @throws ImapException
     */
    public function find(string $messageId): Collection
    {
        $message = $this->imap->find($messageId);

        return $this->parsePayoutMessage($message);
    }

    /**
     * @throws ImapException
     */
    public function query(Carbon $on = null, Carbon $since = null): Collection
    {
        try {
            $query = $this->imap->query()->whereFrom($this->payoutAddress);

            if (filled($on)) {
                $query->whereOn($on);
            }

            if (filled($since)) {
                $query->whereSince($since);
            }

            $messages = $query->get();

            return $this->parsePayoutMessages($messages);
        } catch (Throwable $e) {
            throw new ImapException($e->getMessage());
        }
    }

    /**
     * @throws ImapException
     */
    public function previewMessages(Carbon $on = null, Carbon $since = null): Collection
    {
        return $this->imap->preview($on, $since, $this->payoutAddress);
    }

    private function parsePayoutMessages(MessageCollection $messages): Collection
    {
        $payouts = collect();

        foreach ($messages as $message) {
            $payouts = $payouts->merge($this->parsePayoutMessage($message));
        }

        return $payouts;
    }

    private function parsePayoutMessage(Message $message): Collection
    {
        $payouts = collect();

        foreach ($message->getAttachments() as $attachment) {
            $results = $this->reader->resolvePayoutsAttachment($attachment);

            if ($results->isNotEmpty()) {
                $payouts = $payouts->push($this->reader->resolvePayoutsAttachment($attachment));
            }
        }

        return $payouts;
    }
}