<?php

namespace Eshop\Controllers\Customer\Checkout;

use Eshop\Controllers\Controller;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Requests\LoginRequest;

class CheckoutLoginController extends Controller
{
    public function __invoke(LoginRequest $request, StatefulGuard $guard): RedirectResponse
    {
        $this->loginUsingFortify($request, $guard);
        if (session()->has('errors')) {
            return redirect()->route('login', app()->getLocale());
        }

        return redirect()->route('com::customer.checkout.products.index', app()->getLocale());
    }

    private function loginUsingFortify(LoginRequest $request, StatefulGuard $guard): void
    {
        $controller = new AuthenticatedSessionController($guard);

        $controller->store($request);
    }
}
