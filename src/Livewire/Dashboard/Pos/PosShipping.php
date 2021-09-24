<?php

namespace Eshop\Livewire\Dashboard\Pos;

use Eshop\Actions\Order\FindShippingMethodForOrder;
use Eshop\Actions\Order\ShippingFeeCalculator;
use Eshop\Models\Location\Country;
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
    public array  $shipping       = [];
    public string $email          = "";
    public string $method         = "";
    public string $fee            = "0";
    public float  $weight         = 0;
    public float  $products_value = 0;

    protected $listeners = ['updateTotals'];

    public function updateTotals($weight, $products_value): void
    {
        $this->weight = $weight;
        $this->products_value = $products_value;
    }

    public function updatedFee(): void
    {
        $this->emit('setShippingFee', $this->fee);
    }

    public function updatedShipping($val, $key): void
    {
        if ($key === 'country_id') {
            $this->emit('updateCountry', $val);
        }
    }

    public function updatedMethod(): void
    {
        $calculator = new ShippingFeeCalculator();
        $this->fee = $calculator->handle(CountryShippingMethod::find($this->method), $this->weight, $this->shipping['postcode'] ?? null);
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

    public function getCountriesProperty(): Collection
    {
        return Country::orderBy('name')->get();
    }

    public function calculateShipping(FindShippingMethodForOrder $finder, ShippingFeeCalculator $calculator): void
    {
        $this->validate([
            'shipping.country_id' => ['required', 'integer'],
        ]);
        
        $this->method = $this->shippingOptions->first()?->id;
    }

    public function render(ShippingFeeCalculator $calculator): Renderable
    {
        $base_fee = 0;
        $excess_weight_fee = 0;
        $inaccessible_area_fee = 0;
        if ($this->method) {
            $method = CountryShippingMethod::find($this->method);
            $calculator->handle($method, $this->weight, $this->shipping['postcode'] ?? null);
            $base_fee = $calculator->getBaseFee();
            $inaccessible_area_fee = $calculator->getInaccessibleAreaFee();
            $excess_weight_fee = $calculator->getExcessWeightFee();
        }
        
        return view('eshop::dashboard.pos.wire.pos-shipping', [
            'countries'             => $this->countries,
            'provinces'             => $this->provinces,
            'shippingOptions'       => $this->shippingOptions,
            'selectedOption'        => $this->shippingOptions->firstWhere('id', $this->method),
            'base_fee'              => $base_fee,
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