<?php

namespace Eshop\Livewire\Dashboard\Pos;

use Eshop\Models\Location\Country;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Livewire\Component;

class PosInvoice extends Component
{
    public array $invoice        = [];
    public array $invoiceAddress = [];

    public function getCountriesProperty(): Collection
    {
        return Country::orderBy('name')->get();
    }

    public function getCountryProperty(): null|Country
    {
        if (isset($this->invoiceAddress['country_id']) && $this->invoiceAddress['country_id']) {
            return Country::find($this->invoiceAddress['country_id']);
        }

        return null;
    }

    public function getProvincesProperty(): Collection
    {
        if (empty($this->invoiceAddress['country_id'])) {
            return collect();
        }

        if ($this->country) {
            return $this->country
                ->provinces()
                ->orderBy('name')
                ->pluck('name');
        }

        return collect();
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.pos.wire.pos-invoice', [
            'countries' => $this->countries,
            'provinces' => $this->provinces,
        ]);
    }
}