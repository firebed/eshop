<?php


namespace Eshop\Livewire\Dashboard\Cart\Traits;


use Carbon\Carbon;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\Voucher;
use Eshop\Repository\Contracts\CartContract;
use Eshop\Services\Courier\Courier;
use Eshop\Services\Courier\Couriers;
use Throwable;

trait ManagesVoucher
{
    public ?Voucher $editingVoucher = null;

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
                'is_manual' => false
            ]);

            $this->showSuccessToast('Ο κωδικός αποστολής δημιουργήθηκε με επιτυχία!');
        } catch (Throwable $e) {
            $this->showErrorToast("Σφάλμα", $e->getMessage());
        }

        $this->showBuyVoucherModal = false;
    }

    public function createVoucher(): void
    {
        $cart = Cart::find($this->cart_id);
        $this->editingVoucher = new Voucher(['shipping_method_id' => $cart->shipping_method_id]);
        $this->showVoucherModal = true;
    }

    public function showBuyVoucherModal(): void
    {
        $this->reset('voucher');

        $cart = Cart::find($this->cart_id);
        if ($cart->shippingMethod->courier() === null) {
            $methodName = $cart->shippingMethod?->name;
            $this->showErrorToast("Σφάλμα", "Μη αποδεκτός τρόπος αποστολής" . ($methodName ? " \"" . __("eshop::shipping.$methodName") . "\"" : "") . ".");
            $this->skipRender();
            return;
        }
        $this->voucher['reference_1'] = $cart->id;
        $this->voucher['courier'] = $cart->shippingMethod->courier()->value;
        $this->voucher['pickup_date'] = today()->format('d/m/Y');
        $this->voucher['number_of_packages'] = 1;
        $this->voucher['weight'] = round($cart->parcel_weight / 1000, 2);
        $this->voucher['cod_amount'] = $cart->paymentMethod->isPayOnDelivery() ? round($cart->total, 2) : null;
        $this->voucher['payment_method'] = $cart->paymentMethod->isPayOnDelivery() ? 1 : null;
        $this->voucher['sender'] = config('app.name');
        $this->voucher['customer_name'] = $cart->shippingAddress->fullName;
        $this->voucher['address'] = $cart->shippingAddress->street;
        $this->voucher['address_number'] = $cart->shippingAddress->street_no;
        $this->voucher['postcode'] = str_replace(" ", "", $cart->shippingAddress->postcode);
        $this->voucher['region'] = $cart->shippingAddress->city;
        $this->voucher['cellphone'] = $cart->shippingAddress->phone;
        $this->voucher['country'] = $cart->shippingAddress->country->code;
        $this->voucher['content_type'] = null;

        $this->showBuyVoucherModal = true;
    }

    public function editVoucher(Voucher $voucher): void
    {
        $this->editingVoucher = $voucher;
        $this->showVoucherModal = true;
    }

    public function printVoucher(Voucher $voucher, Courier $courier)
    {
        try {
            $pdf = $courier->printVoucher([
                ['courier' => $voucher->courier, 'number' => $voucher->number]
            ]);

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf;
            }, $voucher->number . '.pdf', ['ContentType' => 'application/pdf']);
        } catch (Throwable $e) {
            $this->showErrorToast("Σφάλμα", $e->getMessage());
        }
    }

    public function saveVoucher(CartContract $contract): void
    {
        $this->validate();

        if ($this->editingVoucher && $this->editingVoucher->exists) {
            $this->editingVoucher->save();
            $this->showVoucherModal = false;
            return;
        }

        $cart = Cart::find($this->cart_id);
        $number = trim($this->editingVoucher->number) ?: null;
        if ($contract->setVoucher($cart->id, $number, $this->editingVoucher->shipping_method_id, true)) {
            $this->showVoucherModal = false;

            $this->showSuccessToast('Voucher saved!');
        } else {
            $this->addError('voucher', 'An error occurred. The voucher code was not updated.');
        }
    }

    public function cancelVoucher(Voucher $voucher, Courier $courier): void
    {
        $method = Couriers::tryFrom($voucher->courier);
        try {
            $courier->deleteVoucher($method, $voucher->number);
            $voucher->delete();

            $this->showSuccessToast("Ο κωδικός αποστολής $voucher->number ακυρώθηκε.");
        } catch (Throwable $e) {
            $this->showErrorToast("Σφάλμα", $e->getMessage());
        }
    }

    public function deleteVoucher(Voucher $voucher): void
    {
        $voucher->delete();
    }

    public function renderingManagesVoucher(): void
    {
        //$this->vouchers = Voucher::where('cart_id', $this->cart_id)->withTrashed()->latest()->get();
    }
}
