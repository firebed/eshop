<?php

namespace Eshop\Services\Skroutz\Listeners;

use Carbon\Carbon;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartEvent;
use Eshop\Models\Cart\Payment;
use Eshop\Models\Notification;
use Eshop\Services\Imap\ImapService;
use Eshop\Services\Skroutz\Events\SkroutzPayoutReceived;
use Eshop\Services\Skroutz\Imports\SkroutzPayoutsImport;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class HandleSkroutzPayouts
{
    /**
     * @throws Exception
     */
    public function handle(SkroutzPayoutReceived $event): void
    {
        $imap = new ImapService();
        $message = $imap->find($event->messageId);

        if ($message === null) {
            return;
        }

        foreach ($message->getAttachments() as $attachment) {
            if ($attachment->getMimeType() !== "application/vnd.ms-excel") {
                continue;
            }

            $filename = Str::random(40) . '.' . $attachment->getExtension();
            Storage::disk('payouts')->put($filename, $attachment->getContent());

            $path = Storage::disk('payouts')->path($filename);
            $payouts = Excel::toCollection(new SkroutzPayoutsImport(), $path);
            $payouts = $payouts->first()->filter(fn($q) => filled($q))->collapse();

            if ($payouts->isEmpty()) {
                continue;
            }

            $this->processPayouts($payouts, $message->getDate()->toDate());
        }
    }

    public function processPayouts(Collection $payouts, Carbon $created_at): void
    {
        $carts = Cart::query()
            ->select('id', 'reference_id', 'total')
            ->whereIn('reference_id', $payouts->keys())
            ->with('shippingAddress', 'payment')
            ->get()
            ->keyBy('reference_id');

        DB::beginTransaction();
        try {
            $metadata = collect();

            foreach ($payouts as $reference => $payout) {
                $cart = $carts->get($reference);

                if ($cart !== null && $cart->payment === null && floats_equal($cart->total, $payout['total'] + $payout['fees'])) {
                    $cart->payment()->save(new Payment([
                        'fees'       => $payout['fees'],
                        'total'      => $payout['total'],
                        'created_at' => $created_at
                    ]));

                    CartEvent::orderPaid($cart->id);
                }

                $metadata->put($reference, [
                    'reference' => $reference,
                    'customer'  => $cart->shippingAddress->fullname ?? $payout['customer'] ?? null,
                    'fees'      => $payout['fees'] ?? 0,
                    'amount'    => $payout['total'],
                ]);
            }

            $total = $payouts->sum('total');
            $notification = sprintf("%s: Λάβατε μια πληρωμή με ποσό %s", 'Skroutz', format_currency($total));
            Notification::create([
                'text'     => $notification,
                'metadata' => [
                    'keyName' => 'reference_id',
                    'payouts' => $metadata
                ]
            ]);

            DB::commit();
        } catch (Throwable) {
            DB::rollBack();
        }
    }
}
