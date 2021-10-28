<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Controller;
use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;

class ProductImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Manage products');
    }
    
    public function index(Product $product): Renderable
    {
        return view('eshop::dashboard.product-images.index', compact('product'));
    }
}
