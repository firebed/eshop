<?php

namespace Eshop\Livewire\Dashboard\Voucher;

use Eshop\Actions\CreateVoucherRequest;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\Voucher;
use Eshop\Repository\Contracts\CartContract;
use Eshop\Services\Courier\ContentType;
use Eshop\Services\Courier\Courier;
use Eshop\Services\Courier\CourierService;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Throwable;

class CreateVoucherModal extends Component
{
    use SendsNotifications;

    public bool    $showModal = false;
    public ?string $icon      = null;
    public bool    $cod       = false;
    public array   $services  = [];
    public int     $courier_id;

    protected $listeners = ['createVoucher'];

    public array $voucher = [
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

    public function createVoucher(Cart $cart, int $packages, CreateVoucherRequest $voucherRequest): void
    {
        $this->reset('voucher');

        if ($cart->voucher !== null) {
            return;
        }

        $courier = $cart->shippingMethod->courier();
        if ($courier === null) {
            $methodName = $cart->shippingMethod?->name;
            $this->showErrorToast("Σφάλμα", "Μη αποδεκτός τρόπος αποστολής" . ($methodName ? " \"" . __("eshop::shipping.$methodName") . "\"" : "") . ".");
            $this->skipRender();
            return;
        }
        $this->courier_id = $courier->value;
        $this->icon = asset("images/" . $courier->icon());
        $this->voucher = $voucherRequest->handle($cart, $packages);
        $this->cod = $cart->paymentMethod->isPayOnDelivery();
        $this->showModal = true;

        $this->loadServices($courier);
        $this->dispatchBrowserEvent('create-voucher-shown');
    }

    public function purchaseVoucher(CartContract $contract, CourierService $courierService)
    {
        $this->resetErrorBag();
        $query = $this->voucher;

        $cart = Cart::findOrFail($query['reference_1']);
        if ($cart->voucher !== null) {
            return;
        }

        try {
            $response = $courierService->createVoucher($this->courier_id, $query);

            $contract->setVoucher($cart->id, $response['number'], $response['courier_id'], false, $response['uuid']);

            $this->showSuccessToast('Ο κωδικός αποστολής δημιουργήθηκε με επιτυχία!');
            $this->showModal = false;


            $this->emit('voucher-created', $response);
            $this->dispatchBrowserEvent('voucher-created', $response);

            $pending_vouchers = Cache::increment('pending-vouchers-count');
            $this->dispatchBrowserEvent('pending-vouchers-updated', $pending_vouchers);
        } catch (Throwable $e) {
            $this->addError('error', $e->getMessage());
        }
    }

    public function updated($k): void
    {
        if ($k === 'courier_id' || $k === 'voucher.country') {
            $this->voucher['services'] = [];
            $courier = Courier::tryFrom($this->courier_id);

            $this->icon = asset("images/" . $courier->icon());
            $this->loadServices($courier);
        }
    }

    private function loadServices(Courier $courier): void
    {
        $this->services = collect($courier->services($this->voucher['country']))
            ->groupBy('group')
            ->map(fn(Collection $c) => $c->pluck('description', 'code'))
            ->toArray();
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.voucher.wire.create', [
            'couriers'     => Courier::cases(),
            'contentTypes' => ContentType::cases(),
        ]);
    }
}