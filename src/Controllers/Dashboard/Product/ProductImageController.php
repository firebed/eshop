<?php

namespace Ecommerce\Controllers\Dashboard\Product;

use Ecommerce\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class ProductImageController extends Controller
{
    public function index(int $productId): Renderable
    {
        return view('com::dashboard.product-images.index', compact('productId'));
    }
}
