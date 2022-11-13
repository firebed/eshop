<?php


namespace Eshop\Livewire\Dashboard\Cart\Traits;


use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\Voucher;
use Eshop\Models\Location\ShippingMethod;
use Eshop\Repository\Contracts\CartContract;
use Eshop\Services\Courier\Courier;
use Illuminate\Support\Collection;
use Throwable;

trait ManagesVoucher
{
    private Collection $vouchers;
    public ?Voucher    $editingVoucher = null;

    public bool $showVoucherModal = false;

    public function purchaseVoucher(Courier $courier): void
    {
        $cart = Cart::find($this->cart_id);
        $method = ShippingMethod::find($this->courier_id);
        
        try {
            $number = $courier->createVoucher([
                'courier'            => $method->courier(),
                'pickup_date'        => today()->addDay(),
                //'time_window'        => '',
                'reference_1'        => (string)$cart->id,
                //'reference_2'        => '',
                'charge_type'        => 1, // Sender
                'number_of_packages' => $this->itemsCount ?? 1,
                'weight'             => max(round($cart->parcel_weight / 1000, 2), 0.5),
                'pod_amount'         => $cart->paymentMethod->isPayOnDelivery() ? round($cart->total, 2) : 0,
                'payment_method'     => 1, // Cash
                //'insurance_amount'   => '',
                'customer_name'      => $cart->shippingAddress->fullName,
                'customer_comments'  => $cart->comments,
                //'customer_email'     => '',
                'address'            => $cart->shippingAddress->street,
                'address_number'     => $cart->shippingAddress->street_no,
                'postcode'           => $cart->shippingAddress->postcode,
                'region'             => $cart->shippingAddress->city,
                //'phone'              => ,
                'cellphone'          => $cart->shippingAddress->phone,
                //'floor'              => '',
                //'company_name'       => '',
                'country'            => $cart->shippingAddress->country->code,
                //'station_id'         => '',
                //'branch_id'          => '',
                'services'           => $cart->paymentMethod->isPayOnDelivery() ? [5] : null, // POD
            ]);

            Voucher::create([
                'cart_id'            => $cart->id,
                'shipping_method_id' => $method->id,
                'number'             => $number,
                'is_manual'          => false
            ]);

            $this->showSuccessToast('Ο κωδικός αποστολής δημιουργήθηκε με επιτυχία!');
        } catch (Throwable $e) {
            $this->showErrorToast("Σφάλμα", $e->getMessage());
        }
    }

    public function createVoucher(): void
    {
        $cart = Cart::find($this->cart_id);
        $this->editingVoucher = new Voucher(['shipping_method_id' => $cart->shipping_method_id]);
        $this->showVoucherModal = true;
    }

    public function editVoucher(Voucher $voucher): void
    {
        $this->editingVoucher = $voucher;
        $this->showVoucherModal = true;
    }

    public function printVoucher(Voucher $voucher, Courier $courier)
    {
        $method = $voucher->shippingMethod->courier();
        try {
            $pdf = $courier->printVoucher([
                ['courier' => $method->value, 'number' => $voucher->number]
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
        $shippingMethod = $voucher->shippingMethod;
        try {
            $courier->cancelVoucher($shippingMethod->courier(), $voucher->number);
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
        $this->vouchers = Voucher::where('cart_id', $this->cart_id)->latest()->get();
    }
}
