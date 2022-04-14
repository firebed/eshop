<?php

namespace Eshop\Commands;

use Carbon\Carbon;
use Eshop\Actions\ReportError;
use Eshop\Actions\SpeedEx\SpeedExPaymentsInfo;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\Payment;
use Eshop\Models\Location\ShippingMethod;
use Eshop\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class SpeedExPaymentsCommand extends Command
{
    protected $signature = 'speedex:payments 
                            { fromDate? : From date }
                            { toDate?   : To   date }';

    protected $description = 'Synchronize payments from SpeedEx courier.';

    private Collection $carts;

    public function handle(SpeedExPaymentsInfo $speedex, ReportError $report): void
    {
        $fromDate = today()->startOfDay();
        if ($this->argument('fromDate')) {
            $fromDate = Carbon::parse($this->argument('fromDate'));
        }

        $toDate = now()->isSameDay($fromDate) ? now() : $fromDate->copy()->endOfDay();
        if ($this->argument('toDate')) {
            $toDate = Carbon::parse($this->argument('toDate'));
        }

        $payments = collect($speedex->handle($fromDate, $toDate));
        if ($payments->isEmpty()) {
            $this->line(sprintf("No payments were made between %s - %s", $fromDate->format('d/m/Y H:i:s'), $toDate->format('d/m/Y H:i:s')));
            return;
        }

        DB::beginTransaction();
        try {
            $this->processPayments($payments);
            DB::commit();
        } catch (Throwable $t) {
            DB::rollBack();
            $report->handle($t->getMessage(), $t->getTraceAsString());
        }
    }

    private function processPayments(Collection $payments): void
    {
        $speedEx = ShippingMethod::firstWhere('name', 'SpeedEx');
        if ($speedEx === null) {
            $this->error("SpeedEx missing from database.");
            return;
        }

        $total = $payments->reduce(static fn($c, $p) => $c + $p->Amount, 0);

        $this->info(sprintf("%d payments were made with total of %.2f", count($payments), $total));

        $this->carts = Cart::query()
            ->where('shipping_method_id', $speedEx->id)
            ->whereIn('voucher', $payments->pluck('ConsignmentNumber'))
            ->whereDoesntHave('payment')
            ->select(['id', 'voucher'])
            ->get()
            ->keyBy('voucher');

        foreach ($payments as $i => $payment) {
            $this->processPayment($i, $payment);
        }
    }

    private function processPayment($index, $payment): void
    {
        $voucher = $payment->ConsignmentNumber;

        if ($this->carts->has($voucher)) {
            $cart = $this->carts->get($voucher);
            $cart->payment()->save(new Payment([
                'metadata'   => $payment,
                'created_at' => $payment->Date
            ]));

            $cart->notifications()->save(new Notification([
                'text'     => "Το ποσό " . format_currency($payment->Amount) . " της παραγγελίας με κωδικό #$cart->id και voucher $voucher καταβλήθηκε από την SpeedEx Courier.",
                'metadata' => $payment
            ]));

            $this->info(sprintf("%d. %s (%.2f)", $index + 1, $voucher, $payment->Amount));
            return;
        }

        $this->warn(sprintf("%d. %s (%.2f) - Order already paid or voucher is missing.", $index + 1, $voucher, $payment->Amount));
    }
}