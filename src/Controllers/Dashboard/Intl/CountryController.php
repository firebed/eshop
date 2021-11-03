<?php

namespace Eshop\Controllers\Dashboard\Intl;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Location\Country;
use Eshop\Requests\Dashboard\Intl\CountryRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class CountryController extends Controller
{
    use WithNotifications;

    public function __construct()
    {
        $this->middleware('can:Manage countries');
    }

    public function index(): Renderable
    {
        $countries = Country::orderBy('name')->get();

        return $this->view('countries.index', compact('countries'));
    }

    public function show(Country $country): Renderable
    {
        return $this->view('countries.show', compact('country'));
    }

    public function create(): Renderable
    {
        return $this->view('countries.create');
    }

    public function store(CountryRequest $request): RedirectResponse
    {
        $country = Country::create($request->validated());

        $this->showSuccessNotification(__("eshop::notifications.saved"));

        return redirect()->route('countries.show', $country);
    }

    public function edit(Country $country): Renderable
    {
        return $this->view('countries.edit', compact('country'));
    }

    public function update(CountryRequest $request, Country $country): RedirectResponse
    {
        $country->update($request->validated());

        $this->showSuccessNotification(__("eshop::notifications.saved"));

        return redirect()->route('countries.show', $country);
    }

    public function destroy(Country $country): RedirectResponse
    {
        $country->delete();

        $this->showSuccessNotification(__("eshop::notifications.deleted"));

        return redirect()->route('countries.index');
    }
}
