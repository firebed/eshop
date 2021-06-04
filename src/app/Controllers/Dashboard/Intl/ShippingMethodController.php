<?php

namespace Ecommerce\Controllers\Dashboard\Intl;

use Ecommerce\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class ShippingMethodController extends Controller
{
    public function index(): Renderable
    {
        return view('dashboard.intl.shipping-methods');
    }
}
