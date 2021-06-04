<?php

namespace App\Http\Controllers\Dashboard\Intl;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class ShippingMethodController extends Controller
{
    public function index(): Renderable
    {
        return view('dashboard.intl.shipping-methods');
    }
}
