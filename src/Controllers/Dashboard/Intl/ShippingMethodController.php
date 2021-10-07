<?php

namespace Eshop\Controllers\Dashboard\Intl;

use Eshop\Controllers\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Location\ShippingMethod;
use Eshop\Requests\Dashboard\Intl\ShippingMethodRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class ShippingMethodController extends Controller
{
    use WithNotifications;

    public function __construct()
    {
        $this->middleware('can:Manage shipping methods');
    }
    
    public function index(): Renderable
    {
        $shippingMethods = ShippingMethod::all();

        return view('eshop::dashboard.shipping-methods.index', compact('shippingMethods'));
    }

    public function show(ShippingMethod $shippingMethod): Renderable
    {
        return view('eshop::dashboard.shipping-methods.show', compact('shippingMethod'));
    }

    public function create(): Renderable
    {
        return view('eshop::dashboard.shipping-methods.create');
    }

    public function store(ShippingMethodRequest $request): RedirectResponse
    {
        $shippingMethod = ShippingMethod::create($request->input());

        $this->showSuccessNotification(__("eshop::notifications.saved"));

        return redirect()->route('shipping-methods.edit', $shippingMethod);
    }

    public function edit(ShippingMethod $shippingMethod): Renderable
    {
        return view('eshop::dashboard.shipping-methods.edit', compact('shippingMethod'));
    }

    public function update(ShippingMethodRequest $request, ShippingMethod $shippingMethod): RedirectResponse
    {
        $shippingMethod->update($request->validated());

        $this->showSuccessNotification(__("eshop::notifications.saved"));

        return back();
    }

    public function destroy(ShippingMethod $shippingMethod): RedirectResponse
    {
        $shippingMethod->delete();

        $this->showSuccessNotification(__("eshop::notifications.deleted"));

        return redirect()->route('shipping-methods.index');
    }
}
