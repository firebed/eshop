<?php

namespace Eshop\Controllers\Customer\Account;

use Eshop\Controllers\Customer\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Location\Address;
use Eshop\Requests\Customer\UserAddressRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class UserAddressController extends Controller
{
    use WithNotifications;

    public function index(): Renderable
    {
        $addresses = auth()->user()?->addresses()->with('country')->get();

        return $this->view('account.address.index', compact('addresses'));
    }

    public function create(): Renderable
    {
        return $this->view('account.address.create');
    }

    public function store(UserAddressRequest $request): RedirectResponse
    {
        auth()->user()?->addresses()->save(new Address($request->validated()));

        $this->showSuccessNotification(__("eshop::notifications.saved"));

        return redirect()->route('account.addresses.index', app()->getLocale());
    }

    public function edit(string $lang, Address $address): Renderable
    {
        return $this->view('account.address.edit', compact('address'));
    }

    public function update(UserAddressRequest $request, string $lang, Address $address): RedirectResponse
    {
        $address->update($request->validated());

        $this->showSuccessNotification(__("eshop::notifications.saved"));

        return back();
    }

    public function destroy(string $lang, Address $address): RedirectResponse
    {
        $address->delete();

        $this->showSuccessNotification(__("eshop::notifications.deleted"));

        return back();
    }
}
