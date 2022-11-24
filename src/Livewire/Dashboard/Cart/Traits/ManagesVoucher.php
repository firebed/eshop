<?php


namespace Eshop\Livewire\Dashboard\Cart\Traits;


use Carbon\Carbon;
use Eshop\Actions\CreateVoucherRequest;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\Voucher;
use Eshop\Repository\Contracts\CartContract;
use Eshop\Services\Courier\Courier;
use Eshop\Services\Courier\Couriers;
use Throwable;

trait ManagesVoucher
{
    public ?Voucher $editingVoucher = null;
    protected array $services       = [];

    public array $voucher = [
        'courier'            => null,
        'pickup_date'        => null,
        'number_of_packages' => 1,
        'weight'             => 0.5,
        'cod_amount'         => null,
        'customer_name'      => "",
        'address'            => "",
        'address_number'     => "",
        'postcode'           => "",
        'region'             => "",
        'cellphone'          => "",
        'country'            => "",
        'content_type'       => null,
        'services'           => [],
    ];

    public bool $showVoucherModal = false;

    public function purchaseVoucher(Courier $courier): void
    {
        $query = $this->voucher;
        $query['pickup_date'] = Carbon::createFromFormat("d/m/Y", $query['pickup_date'])->format('Y-m-d');
        $query['charge_type'] = 1;

        try {
            $voucher = $courier->createVoucher($query);

            Voucher::create([
                'cart_id'   => $this->cart_id,
                'courier'   => $query['courier'],
                'number'    => $voucher['number'],
                'is_manual' => false,
                'meta'      => ['uuid' => $voucher['uuid']]
            ]);

            $this->showSuccessToast('Ο κωδικός αποστολής δημιουργήθηκε με επιτυχία!');
        } catch (Throwable $e) {
            $this->showErrorToast("Σφάλμα", $e->getMessage());
        }

        $this->showBuyVoucherModal = false;
    }

    public function showBuyVoucherModal(CreateVoucherRequest $voucherRequest): void
    {
        $this->reset('voucher');

        $cart = Cart::find($this->cart_id);
        $courier = $cart->shippingMethod->courier();
        if ($courier === null) {
            $methodName = $cart->shippingMethod?->name;
            $this->showErrorToast("Σφάλμα", "Μη αποδεκτός τρόπος αποστολής" . ($methodName ? " \"" . __("eshop::shipping.$methodName") . "\"" : "") . ".");
            $this->skipRender();
            return;
        }
        $this->voucher = $voucherRequest->handle($cart, $courier);

        $this->loadServices($courier, $cart);

        $this->showBuyVoucherModal = true;
    }

    private function loadServices(Couriers $courier, Cart $cart): void
    {
        $this->services = $courier->services($cart->shippingAddress->country->code) ?? [];
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

    public function saveVoucher(CartContract $contract, Courier $courier): void
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

    public function printVoucher(Voucher $voucher, Courier $courier)
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

    public function deleteVoucher(Voucher $voucher, Courier $courier): void
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

    public function updatedVoucher($v, $k): void
    {
        if ($k === 'courier') {
            $cart = Cart::find($this->cart_id);
            $courier = Couriers::tryFrom($v);

            $this->loadServices($courier, $cart);
        }
    }
}
