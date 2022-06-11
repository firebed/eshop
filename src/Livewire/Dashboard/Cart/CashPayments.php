<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\Payment;
use Eshop\Models\Location\ShippingMethod;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class CashPayments extends Component
{
    use SendsNotifications;
    use WithFileUploads;

    public bool  $showCashPaymentsModal = false;
    public ?int  $shipping_method_id    = null;
    public       $files                 = [];
    public array $valid_cart_ids        = [];

    private Collection $vouchers;
    private Collection $valid_carts;
    private Collection $carts;

    protected $listeners = ['showCashPaymentsModal'];

    public function boot()
    {
        $this->carts = collect();
        $this->valid_carts = collect();
        $this->vouchers = collect();
    }

    public function showCashPaymentsModal(): void
    {
        $this->reset('files', 'valid_cart_ids');
        $this->vouchers = collect();

        $this->showCashPaymentsModal = true;
    }

    public function updated(): void
    {
        $this->validate([
            'files.*' => ['file', 'mimes:xls,xlsx', 'max:2048'], // 2MB Max
        ]);

        [$voucherIndex, $totalIndex] = $this->getColumnIndex();
        if ($voucherIndex === null || $totalIndex === null) {
            $this->addError('error', 'Δεν βρέθηκαν ρυθμίσεις για την επιλεγμένη μεταφορική εταιρεία.');
            return;
        }

        foreach ($this->files as $file) {
            $imported = $this->readFile($file, $voucherIndex, $totalIndex);

            $this->vouchers = $this->vouchers->merge($imported);
        }

        if ($this->vouchers->isNotEmpty()) {
            $this->updateCarts();
        }
    }

    private function updateCarts(): void
    {
        $this->carts = Cart::where('shipping_method_id', $this->shipping_method_id)
            ->whereIn('voucher', $this->vouchers->keys())
            ->select(['id', 'voucher', 'total'])
            ->with('payment')
            ->get()
            ->keyBy('voucher');

        $this->valid_carts = $this->carts
            ->filter(fn($cart) => !$cart->isPaid())
            ->filter(fn($c) => $this->equalFloats($c->total, $this->vouchers->get($c->voucher)));

        $this->valid_cart_ids = $this->valid_carts->pluck('id')->toArray();
    }

    private function readFile($file, $voucherIndex, $totalIndex)
    {
        $imported = Excel::toCollection(null, $file)->first();

        $imported->shift();
        $imported->pop();

        $imported = $imported->mapWithKeys(function ($row) use ($voucherIndex, $totalIndex) {
            $voucher = $row[$voucherIndex] ?? null;
            $total = $row[$totalIndex] ?? null;

            return [$voucher => $total];
        })->filter();

        $this->vouchers = $this->vouchers->merge($imported);
    }

    private function getShippingMethods(): Collection
    {
        return ShippingMethod::where('is_courier', true)->get();
    }

    public function render(): Renderable
    {
        $shippingMethods = $this->getShippingMethods();
        if (empty($this->shipping_method_id)) {
            $this->shipping_method_id = $shippingMethods->first()?->id;
        }

        return view('eshop::dashboard.cart.wire.cart-cash-payments', [
            'carts'           => $this->carts,
            'vouchers'        => $this->vouchers,
            'shippingMethods' => $shippingMethods,
            'total_files'     => count($this->files),
            'total_vouchers'  => $this->vouchers->count(),
            'valid_carts'     => $this->valid_carts,
        ]);
    }

    public function equalFloats($a, $b): bool
    {
        return abs($a - $b) < PHP_FLOAT_EPSILON;
    }

    public function save(): void
    {
        if (empty($this->valid_cart_ids)) {
            $this->showErrorToast('Δεν υπάρχουν vouchers για απόδοση.');
            return;
        }

        DB::transaction(function () {
            $carts = Cart::findMany($this->valid_cart_ids);
            foreach ($carts as $cart) {
                $cart->payment()->save(new Payment());
            }
        });

        $this->showCashPaymentsModal = false;
        $this->showSuccessToast(sprintf("Αποδόθηκαν %d vouchers", count($this->valid_cart_ids)));

        $this->emit('cartStatusUpdated');
        $this->emit('cartsTableUpdated');
    }

    private function getColumnIndex(): ?array
    {
        $method = ShippingMethod::find($this->shipping_method_id);

        return match ($method->name) {
            'Courier Center' => [11, 21],
            default => null
        };
    }
}