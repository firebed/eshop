<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\Voucher;
use Eshop\Repository\Contracts\CartContract;
use Eshop\Services\Courier\ContentType;
use Eshop\Services\Courier\Courier;
use Eshop\Services\Courier\CourierService;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Livewire\Component;
use Throwable;

class TrackAndTrace extends Component
{
    use SendsNotifications;

    public bool     $showTrace              = false;
    public bool     $showVoucherModal       = false;
    public bool     $showDeleteVoucherModal = false;
    public bool     $propagate_delete       = true;
    public bool     $saveOnMyShipping       = true;
    public int      $cart_id;
    public int      $courier_id;
    public ?Voucher $editingVoucher         = null;

    private Collection $checkpoints;

    protected array $rules = [
        'editingVoucher.courier' => ['required', 'integer'],
        'editingVoucher.number'  => 'required|string|max:255'
    ];

    protected $listeners = ['voucher-created' => '$refresh'];
    
    public function trace(Voucher $voucher, CourierService $courier)
    {
        try {
            $this->checkpoints = $courier->trace($voucher);
            $this->showTrace = true;
        } catch (Throwable $e) {
            $this->showErrorToast("Σφάλμα", $e->getMessage());
        }
    }

    public function createVoucher(): void
    {
        $cart = Cart::find($this->cart_id);
        $this->editingVoucher = new Voucher([
            'courier' => $cart->shippingMethod->courier()->value ?? null
        ]);

        $this->saveOnMyShipping = true;
        $this->showVoucherModal = true;
    }

    public function editVoucher(Voucher $voucher): void
    {
        $this->editingVoucher = $voucher;
        $this->showVoucherModal = true;
    }

    public function saveVoucher(CartContract $contract, CourierService $courier): void
    {
        $this->validate();

        if ($this->editingVoucher && $this->editingVoucher->exists) {
            $courier->updateManualVoucher($this->editingVoucher, [
                'courier'    => $this->editingVoucher->courier,
                'number'     => $this->editingVoucher->number,
                'cod_amount' => 0,
            ]);

            $this->editingVoucher->save();
            $this->showVoucherModal = false;
            $this->showSuccessToast('Voucher saved!');
            return;
        }

        $cart = Cart::find($this->cart_id);
        $number = trim($this->editingVoucher->number) ?: null;

        if ($this->saveOnMyShipping) {
            $response = $courier->createManualVoucher([
                'courier'     => $this->editingVoucher->courier,
                'reference_1' => $cart->id,
                'number'      => $number,
                'cod_amount'  => 0,
            ]);

            $meta = ['uuid' => $response['uuid']];
        }

        if ($contract->setVoucher($cart->id, $number, $this->editingVoucher->courier, true, $meta ?? [])) {
            $this->showVoucherModal = false;

            $this->showSuccessToast('Voucher saved!');
        } else {
            $this->addError('voucher', 'An error occurred. The voucher code was not updated.');
        }
    }

    public function printVoucher(Voucher $voucher, CourierService $courier)
    {
        try {
            $pdf = $courier->printVoucher(collect([$voucher]));

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf;
            }, $voucher->number . '.pdf', ['ContentType' => 'application/pdf']);
        } catch (Throwable $e) {
            $this->showErrorToast("Σφάλμα", $e->getMessage());
        }
    }

    public function deleteVoucher(Voucher $voucher, CourierService $courier): void
    {
        try {
            $courier->deleteVoucher($voucher, $this->propagate_delete);

            $voucher->delete();

            $this->showSuccessToast("Ο κωδικός αποστολής $voucher->number ακυρώθηκε.");
            $this->showDeleteVoucherModal = false;
        } catch (Throwable $e) {
            $this->showErrorToast("Σφάλμα", $e->getMessage());
        }
    }

    public function render(): Renderable
    {
        $cart = Cart::find($this->cart_id);
        
        return view('eshop::dashboard.cart.wire.track-and-trace', [
            'checkpoints'    => $this->checkpoints ?? collect(),
            'icons'          => collect(Courier::cases())->mapWithKeys(fn($c) => [$c->value => asset('images/' . $c->icon())]),
            'contentTypes'   => ContentType::cases(),
            'cart'           => $cart,
            'currentVoucher' => $cart->voucher()->first()
        ]);
    }
}