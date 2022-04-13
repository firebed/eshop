<?php

namespace Eshop\Commands;

use Carbon\Carbon;
use Eshop\Actions\Acs\AcsPaymentsInfo;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Location\ShippingMethod;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class AcsPaymentsCommand extends Command
{
    protected $signature = 'acs:payments { date? : Target date }';

    protected $description = 'Synchronize payments from ACS courier.';

    private Collection $carts;
    
    public function handle(AcsPaymentsInfo $acsPayments): void
    {
        $date = today();
        if ($inputDate = $this->argument('date')) {
            $date = Carbon::parse($inputDate);
        }

        $payments = collect($acsPayments->handle($date));
        if ($payments->isEmpty()) {
            $this->warn("No payments were made in " . $date->format('d/m/Y'));
            return;
        }

        $this->processPayments($payments, $date);
    }

    private function processPayments(Collection $payments, $date): void
    {
        $acs = ShippingMethod::firstWhere('name', 'ACS Courier');
        if ($acs === null) {
            $this->error("ACS missing from database.");
            return;
        }
        
        $total = $payments->reduce(static fn($c, $p) => $c + $p['Parcel_COD_Amount'], 0);

        $this->info(sprintf("%d payments were made with total of %.2f", count($payments), $total));

        $this->carts = Cart::query()
            ->where('shipping_method_id', $acs->id)
            ->whereIn('voucher', $payments->pluck('POD'))
            ->select(['id', 'voucher'])
            ->get()
            ->keyBy('voucher');

        foreach ($payments as $i => $payment) {
            $this->processPayment($i, $payment, $date);
        }
    }

    private function processPayment($index, $payment, $date): void
    {
        $voucher = $payment['POD'];

        if ($this->carts->has($voucher)) {
            $cart = $this->carts->get($voucher);
            $cart->payment()->updateOrCreate([], [
                'metadata' => $payment,
                'created_at' => $date
            ]);
            
            $this->info(sprintf("%d. %s (%.2f)", $index + 1, $voucher, $payment['Parcel_COD_Amount']));
            return;
        }

        $this->warn(sprintf("%d. %s (%.2f) - Database record missing.", $index + 1, $voucher, $payment['Parcel_COD_Amount']));
    }
}