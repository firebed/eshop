<?php

namespace Eshop\Controllers\Dashboard\Intl;

use Eshop\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class PaymentMethodController extends Controller
{
    public function index(): Renderable
    {
        return view('eshop::dashboard.intl.payment-methods');
    }
}
