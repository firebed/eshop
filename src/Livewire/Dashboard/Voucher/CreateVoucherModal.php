<?php

namespace Eshop\Livewire\Dashboard\Voucher;

use Eshop\Actions\CreateVoucherRequest;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\Voucher;
use Eshop\Services\Courier\Courier;
use Eshop\Services\Courier\Couriers;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;
use Throwable;

class CreateVoucherModal extends Component
{
    use SendsNotifications;

    public bool $showModal = false;

    private ?string $icon = null;

    protected $listeners = [
        'createVoucher'
    ];

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

    private array $services = [];

    public function createVoucher(Cart $cart, CreateVoucherRequest $voucherRequest): void
    {
        $courier = $cart->shippingMethod->courier();
        $this->icon = asset("images/" . $courier->icon());

        $this->voucher = $voucherRequest->handle($cart, $courier);
        $this->loadServices($courier, $cart);

        $this->showModal = true;
    }

    public function purchaseVoucher(Courier $courier)
    {
        $query = $this->voucher;
        $query['charge_type'] = 1;

        try {
            $voucher = $courier->createVoucher($query);

            Voucher::create([
                'cart_id'   => $voucher['reference_1'],
                'courier'   => $voucher['courier'],
                'number'    => $voucher['number'],
                'is_manual' => false,
                'meta'      => ['uuid' => $voucher['uuid']]
            ]);

            $this->showSuccessToast('Ο κωδικός αποστολής δημιουργήθηκε με επιτυχία!');
            $this->showBuyVoucherModal = false;
        } catch (Throwable $e) {
            $this->addError('error', $e->getMessage());
        }
    }

    private function loadServices(Couriers $courier, Cart $cart): void
    {
        $this->services = $courier->services($cart->shippingAddress->country->code) ?? [];
    }


    public function render(): Renderable
    {
        return view('eshop::dashboard.voucher.wire.create', [
            'services' => $this->services,
            'icon'     => $this->icon,
        ]);
    }
}