<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Models\Product\Product;
use Eshop\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Product $product
     * @return Renderable
     */
    public function index(Product $product): Renderable
    {
        return view('com::dashboard.product-variant.index', compact('product'));
    }
}
