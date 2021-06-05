<?php

namespace Eshop\Controllers\Dashboard\Intl;

use Eshop\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class ShippingMethodController extends Controller
{
    public function index(): Renderable
    {
        return view('com::dashboard.intl.shipping-methods');
    }
}
