<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class ProductTrashController extends Controller
{
    public function __invoke(): Renderable
    {
        return view('eshop::dashboard.product.trashed.index');
    }
}
