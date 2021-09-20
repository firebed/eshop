<?php

namespace App\Http\Controllers\Account;

use App\Http\Requests\UserAddressRequest;
use Eshop\Controllers\Controller;
use Eshop\Models\Location\Address;
use Eshop\Models\Location\Country;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class UserAddressController extends Controller
{
    public function index(): Renderable
    {
        $addresses = auth()->user()->addresses()->with('country')->get();

        return view('account.address.index', [
            'addresses' => $addresses
        ]);
    }

    public function create(): Renderable
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

    public function edit(string $lang, Address $address): Renderable
    {
        return view('account.address.edit', [
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
