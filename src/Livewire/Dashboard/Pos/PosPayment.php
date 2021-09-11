<?php

namespace Eshop\Livewire\Dashboard\Pos;

use Eshop\Actions\Order\PaymentFeeCalculator;
use Eshop\Models\Location\Country;
use Eshop\Models\Location\PaymentMethod;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class PosPayment extends Component
{
    public string $method = "";
    public string $fee    = "0";

    public ?string $country_id = null;
    public float   $products_value;

    protected $listeners = ['updateCountry', 'updateTotals'];

    public function mount(array $items, float $shipping_fee): void
    {
        $this->products_value = array_reduce($items, static function ($carry, $item) {
            return $carry + $item['quantity'] * $item['price'] * (1 - $item['discount']);
        }, $shipping_fee + (float)$this->fee);
    }

    public function updateCountry(null|int $country_id): void
    {
        $this->country_id = $country_id;
    }

    public function updateTotals(int $weight, float $products_value): void
    {
        $this->products_value = $products_value;
    }

    public function updatedFee(): void
    {
        $this->emit('setPaymentFee', $this->fee);
    }

    public function calculatePayment(PaymentFeeCalculator $calculator): void
    {
        if (empty($this->country_id)) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'body' => "Παρακαλώ επιλέξτε χώρα."]);
            return;
        }

        [$method, $fee] = $calculator->handle($this->country, $this->products_value, $this->method ?? null);

        $this->method = $method->payment_method_id;
        $this->fee = $fee;
        $this->updatedFee();
    }

    public function getCountryProperty(): null|Country
    {
        return Country::find($this->country_id);
    }

    public function render(): Renderable
    {
        $paymentMethods = PaymentMethod::all();

        return view('eshop::dashboard.pos.wire.pos-payment', [
            'country'        => $this->country,
            'paymentMethods' => $paymentMethods,
        ]);
    }
}