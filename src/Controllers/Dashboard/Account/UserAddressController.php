<?php

namespace Eshop\Controllers\Dashboard\Account;

use Eshop\Controllers\Controller;
use Eshop\Models\Location\Address;
use Eshop\Models\Location\Country;
use Eshop\Requests\Customer\UserAddressRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserAddressController extends Controller
{
    public function index(): View
    {
        $addresses = auth()->user()->addresses()->with('country')->get();

        return view('eshop::customer.account.address.index', [
            'addresses' => $addresses
        ]);
    }

    public function create(): View
    {
        return view('eshop::customer.account.address.create', [
            'countries' => Country::visible()->get()
        ]);
    }

    public function store(UserAddressRequest $request): RedirectResponse
    {
        auth()->user()->addresses()->save(new Address($request->validated()));

        return redirect()
            ->route('account.addresses.index', app()->getLocale())
            ->with('success', __("The new address was saved!"));
    }

    public function edit(string $lang, Address $address): View
    {
        return view('eshop::customer.account.address.edit', [
            'address'   => $address,
            'countries' => Country::visible()->get()
        ]);
    }

    public function update(UserAddressRequest $request, string $lang, Address $address): RedirectResponse
    {
        $address->update($request->validated());

        return back()->with('success', __("The address was saved"));
    }

    public function destroy(string $lang, Address $address): RedirectResponse
    {
        $address->delete();

        return back()->with('success', __("The address was deleted!"));
    }
}
