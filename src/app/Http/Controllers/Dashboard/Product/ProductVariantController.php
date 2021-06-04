<?php

namespace App\Http\Controllers\Dashboard\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
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
        return view('dashboard.product-variant.index', compact('product'));
    }
}
