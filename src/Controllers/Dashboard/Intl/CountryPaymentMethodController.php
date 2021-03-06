<?php

namespace Eshop\Controllers\Dashboard\Intl;

use Eshop\Controllers\Dashboard\Controller;
use Illuminate\Contracts\Support\Renderable;

class CountryPaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Manage country payment methods');
    }

    public function __invoke(): Renderable
    {
        return $this->view('intl.country-payment-methods');
    }
}