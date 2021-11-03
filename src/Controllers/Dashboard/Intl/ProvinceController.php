<?php

namespace Eshop\Controllers\Dashboard\Intl;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Location\Country;
use Eshop\Models\Location\Province;
use Eshop\Requests\Dashboard\Intl\ProvinceRequest;
use Illuminate\Http\RedirectResponse;

class ProvinceController extends Controller
{
    use WithNotifications;

    public function __construct()
    {
        $this->middleware('can:Manage countries');
    }

    public function store(ProvinceRequest $request, Country $country): RedirectResponse
    {
        $country->provinces()->save(new Province($request->validated()));

        $this->showSuccessNotification(__("eshop::notifications.saved"));
        return back();
    }

    public function update(ProvinceRequest $request, Province $province): RedirectResponse
    {
        $province->update($request->validated());

        $this->showSuccessNotification(__("eshop::notifications.saved"));
        return back();
    }

    public function destroy(Province $province): RedirectResponse
    {
        $province->delete();

        $this->showSuccessNotification(__("eshop::notifications.deleted"));
        return back();
    }
}