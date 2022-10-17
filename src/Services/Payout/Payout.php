<?php

namespace Eshop\Services\Payout;

use Carbon\Carbon;
use Eshop\Models\Cart\CartEvent;
use Eshop\Models\Cart\Payment;
use Eshop\Models\Cart\Payout as PayoutModel;
use Eshop\Models\Notification;
use Eshop\Services\Imap\Exceptions\ImapException;
use Eshop\Services\Imap\ImapService;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;
use Webklex\PHPIMAP\Attachment;
use Webklex\PHPIMAP\Message;
use Webklex\PHPIMAP\Support\MessageCollection;

class Payout
{
    protected string $disk = 'payouts';

    private ImapService $imap;

    public function __construct(readonly private ?string $payoutAddress, readonly PayoutReader $reader)
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
            if (!$this->reader->validatePayoutAttachment($attachment)) {
                continue;
            }

            $path = $this->saveAttachment($attachment);

            $results = $this->reader->handlePayoutsAttachment($path);

            $this->deleteAttachment($path);

            if ($results->isNotEmpty()) {
                $payouts = $payouts->push($results);
            }
        }

        return $payouts;
    }

    public function saveAttachment(Attachment $attachment): string
    {
        $filename = Str::random(40) . '.' . $attachment->getExtension();
        $this->disk()->put($filename, $attachment->getContent());

        return $filename;
    }

    public function deleteAttachment(string $filename): void
    {
        $this->disk()->delete($filename);
    }

    public function disk(): Filesystem
    {
        return Storage::disk($this->disk);
    }

    /**
     * @throws ImapException
     */
    public function processMessage(string $messageId, Model $originator, callable $cartsResolver): void
    {
        $message = $this->imap->find($messageId);

        if ($message === null) {
            return;
        }

        $messageId = $message->getMessageId()->first();
        $date = $message->getDate()->first();

        foreach ($message->getAttachments() as $attachment) {
            if (!$this->reader->validatePayoutAttachment($attachment)) {
                continue;
            }

            $filename = $this->saveAttachment($attachment);
            $payouts = $this->reader->handlePayoutsAttachment($filename);

            if ($payouts->isEmpty()) {
                continue;
            }

            $this->processPayouts($messageId, $payouts, $originator, $cartsResolver, $date, $filename);
        }
    }

    public function processPayouts(string $reference_id, Collection $payouts, Model $originator, callable $cartsResolver, Carbon $payoutDate, string $attachment = null): void
    {
        [$keyName, $carts] = $cartsResolver($payouts->keys());

        DB::beginTransaction();
        try {
            $payoutReference = $originator->payouts()->save(new PayoutModel([
                'reference_id' => $reference_id,
                'orders'       => $payouts->count(),
                'total'        => $payouts->sum('total'),
                'fees'         => $payouts->sum('fees'),
                'attachment'   => $attachment
            ]));

            $metadata = [];

            foreach ($payouts as $reference => $payout) {
                $cart = $carts->get($reference);

                if ($cart !== null && $cart->payment === null && floats_equal($cart->total, $payout['total'] + $payout['fees'])) {
                    $cart->payment()->save(new Payment([
                        'payout_id'  => $payoutReference->id,
                        'fees'       => $payout['fees'],
                        'total'      => $payout['total'],
                        'created_at' => $payoutDate
                    ]));

                    CartEvent::orderPaid($cart->id);
                }

                $metadata[] = [
                    'reference'     => $reference,
                    'customer_name' => $cart->shippingAddress->fullname ?? $payout['customer_name'] ?? null,
                    'fees'          => $payout['fees'] ?? 0,
                    'total'         => $payout['total'],
                ];
            }

            $total = $payouts->sum('total');
            $notification = sprintf("%s: Λάβατε μια πληρωμή με ποσό %s", $originator->name, format_currency($total));
            Notification::create([
                'text'     => $notification,
                'metadata' => [
                    'keyName'    => $keyName,
                    'payout_id'  => $payoutReference->id,
                    'attachment' => $attachment,
                    'payouts'    => $metadata
                ]
            ]);

            DB::commit();
        } catch (Throwable) {
            if (filled($attachment)) {
                $this->deleteAttachment($attachment);
            }

            DB::rollBack();
        }
    }
}