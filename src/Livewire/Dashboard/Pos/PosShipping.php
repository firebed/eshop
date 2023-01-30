<?php

namespace Eshop\Livewire\Dashboard\Pos;

use Eshop\Actions\Order\ShippingFeeCalculator;
use Eshop\Models\Location\Address;
use Eshop\Models\Location\Country;
use Eshop\Models\Location\CountryPaymentMethod;
use Eshop\Models\Location\CountryShippingMethod;
use Eshop\Models\Location\ShippingMethod;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Livewire\Component;

/**
 * @property Country    country
 * @property Collection provinces
 * @property Collection countries
 * @property Collection shippingMethods
 */
class PosShipping extends Component
{
    public string            $phoneSearch     = '';
    public array             $shipping        = [];
    public string            $email           = "";
    public string            $shipping_method = "";
    public string            $payment_method  = "";
    public float|string|null $shipping_fee    = 0;
    public float|string|null $payment_fee     = 0;
    public float             $weight          = 0;
    public float             $products_value  = 0;

    protected $listeners = ['updateTotals'];

    public function updateTotals($weight, $products_value): void
    {
        $this->weight = $weight;
        $this->products_value = $products_value;
    }

    public function searchClient()
    {
        $address = Address::where('addressable_type', 'cart')
            ->whereNotNull('phone')
            ->with('addressable')
            ->where('phone', $this->phoneSearch)
            ->first();
        
        if ($address !== null) {
            $this->shipping = [
                "first_name" => $address->first_name,
                "last_name"  => $address->last_name,
                "phone"      => $address->phone,
                "country_id" => $address->country_id,
                "province"   => $address->province,
                "city"       => $address->city,
                "postcode"   => $address->postcode,
                "street"     => $address->street,
                "street_no"  => $address->street_no,
            ];
            
            $this->email = $address->addressable->email ?? '';
        }
    }

    public function updatedShippingMethod(): void
    {
        $method = CountryShippingMethod::find($this->shipping_method);
        if ($method === null) {
            return;
        }

        $calculator = new ShippingFeeCalculator();
        $this->shipping_fee = $calculator->handle($method, $this->weight, $this->shipping['postcode'] ?? null);

        if (!$this->paymentOptions->contains('id', $this->payment_method)) {
            $payment = $this->paymentOptions->first();
            $this->payment_method = $payment?->id;
            $this->payment_fee = $payment?->fee ?? 0;
        }

        $this->fireProcessingFeesEvent();
    }

    public function updatedPaymentMethod(): void
    {
        $method = CountryPaymentMethod::find($this->payment_method);
        if ($method === null) {
            return;
        }

        $this->payment_fee = $method?->fee ?? 0;

        $this->fireProcessingFeesEvent();
    }

    public function updated($name): void
    {
        if ($name === 'payment_fee' || $name === 'shipping_fee') {
            $this->{$name} = $this->toFloat($this->{$name});

            $this->fireProcessingFeesEvent();
        }
    }

    private function fireProcessingFeesEvent(): void
    {
        $this->emit('setProcessingFees', ($this->shipping_fee ?? 0) + ($this->payment_fee ?? 0));
    }

    private function toFloat($float): float
    {
        $float = preg_replace('/[^\d,]/', '', $float);
        return (float)str_replace(',', '.', $float ?? 0);
    }

    public function getProvincesProperty(): Collection
    {
        if (empty($this->shipping['country_id'])) {
            return collect();
        }

        if ($this->country) {
            return $this->country
                ->provinces()
                ->where('shippable', true)
                ->orderBy('name')
                ->pluck('name');
        }

        return collect();
    }

    public function getCountryProperty(): null|Country
    {
        if (isset($this->shipping['country_id']) && $this->shipping['country_id']) {
            return Country::find($this->shipping['country_id']);
        }

        return null;
    }

    public function getShippingOptionsProperty(ShippingFeeCalculator $calculator): Collection
    {
        if (isset($this->shipping['country_id']) && $this->shipping['country_id']) {
            $country = $this->country;
            $country->shippingOptions->load('shippingMethod');
            $options = $country->filterShippingOptions($this->products_value);
            foreach ($options as $option) {
                $option->total_fee = $calculator->handle($option, $this->weight, $this->shipping['postcode'] ?? null);
                $area = $option->shippingMethod->inaccessibleAreas()->firstWhere('postcode', $this->shipping['postcode'] ?? null);
                if ($area?->type !== null) {
                    $option->setRelation('area', $area);
                }
            }

            return $options->reject(fn($method) => $method->area === null && $method->inaccessible_area_fee > 0);
        }

        return collect();
    }

    public function getPaymentOptionsProperty(): Collection
    {
        if (!empty($this->shipping['country_id'])) {
            $country = $this->country;
            $country->paymentOptions->load('paymentMethod', 'shippingMethod');

            $selected_shipping_method_id = $this->shippingOptions->firstWhere('id', $this->shipping_method)?->shipping_method_id;
            return $country->filterPaymentOptions($this->products_value)
                ->filter(fn($m) => $m->shipping_method_id === null || $m->shipping_method_id === $selected_shipping_method_id);
        }

        return collect();
    }

    public function getCountriesProperty(): Collection
    {
        return Country::orderBy('name')->get();
    }

    public function render(ShippingFeeCalculator $calculator): Renderable
    {
        $base_shipping_fee = 0;
        $excess_weight_fee = 0;
        $inaccessible_area_fee = 0;
        if (filled($this->shipping_method)) {
            $method = CountryShippingMethod::find($this->shipping_method);
            $calculator->handle($method, $this->weight, $this->shipping['postcode'] ?? null);
            $base_shipping_fee = $calculator->getBaseFee();
            $inaccessible_area_fee = $calculator->getInaccessibleAreaFee();
            $excess_weight_fee = $calculator->getExcessWeightFee();
        }

        return view('eshop::dashboard.pos.wire.pos-shipping', [
            'countries'             => $this->countries,
            'provinces'             => $this->provinces,
            'shippingOptions'       => $this->shippingOptions,
            'paymentOptions'        => $this->paymentOptions,
            'selectedOption'        => $this->shippingOptions->firstWhere('id', $this->shipping_method),
            'base_shipping_fee'     => $base_shipping_fee,
            'excess_weight_fee'     => $excess_weight_fee,
            'inaccessible_area_fee' => $inaccessible_area_fee
        ]);
    }

    private function checkForInaccessibleArea(): void
    {
        $this->is_inaccessible_area = false;

        if (blank($this->method) || blank($this->shipping['postcode'])) {
            return;
        }

        $courier = ShippingMethod::find($this->method);
        $area = $courier?->inaccessibleAreas()->firstWhere('postcode', $this->shipping['postcode']);
        if ($area?->type === 'charge') {
            $this->is_inaccessible_area = true;
        }
    }
}