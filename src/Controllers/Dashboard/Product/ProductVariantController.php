<?php

namespace Ecommerce\Controllers\Dashboard\Product;

use Ecommerce\Models\Product\Product;
use Ecommerce\Controllers\Controller;
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
