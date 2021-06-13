<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class ManufacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('eshop::dashboard.manufacturer.index');
    }
}
