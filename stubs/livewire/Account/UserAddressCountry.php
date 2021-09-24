<?php

namespace App\Http\Livewire\Account;

use Eshop\Models\Location\Country;
use Eshop\Models\Location\Province;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class UserAddressCountry extends Component
{
    public string $country_id = "";
    public string $province = "";

    public function render(): Renderable
    {
        $provinces = collect();

        if (!empty($this->country_id)) {
            $provinces = Province::where('country_id', $this->country_id)
                ->where('shippable', true)
                ->orderBy('name')
                ->pluck('name');
        }

        $countries = Country::visible()->orderBy('name')->get();

        return view('account.address.wire.country_province', [
            'countries' => $countries,
            'provinces' => $provinces
        ]);
    }
}
