<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Dashboard\Controller;
use Illuminate\Contracts\Support\Renderable;

class ManufacturerController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:Manage manufacturers');
    }

    public function index(): Renderable
    {
        return $this->view('manufacturer.index');
    }
}
