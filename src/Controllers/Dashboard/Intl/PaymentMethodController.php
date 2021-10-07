<?php

namespace Eshop\Controllers\Dashboard\Intl;

use Eshop\Controllers\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Location\PaymentMethod;
use Eshop\Requests\Dashboard\Intl\PaymentMethodRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class PaymentMethodController extends Controller
{
    use WithNotifications;

    public function __construct()
    {
        $this->middleware('can:Manage payment methods');
    }
    
    public function index(): Renderable
    {
        $paymentMethods = PaymentMethod::all();

        return view('eshop::dashboard.payment-methods.index', compact('paymentMethods'));
    }

    public function create(): Renderable
    {
        return view('eshop::dashboard.payment-methods.create');
    }

    public function store(PaymentMethodRequest $request): RedirectResponse
    {
        $paymentMethod = PaymentMethod::create($request->input());

        $this->showSuccessNotification(__("eshop::notifications.saved"));

        return redirect()->route('payment-methods.edit', $paymentMethod);
    }

    public function edit(PaymentMethod $paymentMethod): Renderable
    {
        return view('eshop::dashboard.payment-methods.edit', compact('paymentMethod'));
    }

    public function update(PaymentMethodRequest $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        $paymentMethod->update($request->validated());

        $this->showSuccessNotification(__("eshop::notifications.saved"));

        return back();
    }

    public function destroy(PaymentMethod $paymentMethod): RedirectResponse
    {
        $paymentMethod->delete();

        $this->showSuccessNotification(__("eshop::notifications.deleted"));

        return redirect()->route('payment-methods.index');
    }
}
