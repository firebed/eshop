<?php

namespace Ecommerce\Controllers\Dashboard\Intl;

use Ecommerce\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('com::dashboard.intl.countries');
    }
}
