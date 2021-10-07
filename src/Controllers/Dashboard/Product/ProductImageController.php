<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class ProductImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Manage products');
    }
    
    public function index(int $productId): Renderable
    {
        return view('eshop::dashboard.product-images.index', compact('productId'));
    }
}
