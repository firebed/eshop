<?php

namespace Eshop\Controllers\Dashboard\Intl;

use Eshop\Controllers\Dashboard\Controller;
use Illuminate\Contracts\Support\Renderable;

class CountryShippingMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Manage country shipping methods');
    }

    public function __invoke(): Renderable
    {
        return $this->view('intl.country-shipping-methods');
    }
}