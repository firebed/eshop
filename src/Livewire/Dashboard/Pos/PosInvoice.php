<?php

namespace Eshop\Livewire\Dashboard\Pos;

use Eshop\Actions\VatSearch;
use Eshop\Models\Location\Country;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Livewire\Component;
use SoapFault;

class PosInvoice extends Component
{
    public array  $invoice        = [];
    public array  $invoiceAddress = [];
    public string $vatSearch      = "";

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

    public function searchVatNumber(VatSearch $search)
    {
        if (blank($this->vatSearch)) {
            return;
        }

        try {
            $results = $search->handle($this->vatSearch);
            $this->invoice['vat_number'] = $results['vat'];
            $this->invoice['name'] = $results['name'];
            $this->invoice['tax_authority'] = $results['tax_authority'];
            $this->invoice['job'] = $results['job'];
            $this->invoiceAddress['city'] = $results['city'];
            $this->invoiceAddress['postcode'] = $results['postcode'];
            $this->invoiceAddress['street'] = $results['street'];
            $this->invoiceAddress['street_no'] = $results['street_number'];
            $this->invoiceAddress['country_id'] = Country::firstWhere('code', 'GR')->id;
        } catch (SoapFault $e) {
        }
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.pos.wire.pos-invoice', [
            'countries' => $this->countries,
            'provinces' => $this->provinces,
        ]);
    }
}