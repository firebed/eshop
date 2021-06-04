<?php

namespace App\Http\Controllers\Dashboard\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('dashboard.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create(): Renderable
    {
        return view('dashboard.product.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function createGroup(): Renderable
    {
        return view('dashboard.product.create-group');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return Renderable
     */
    public function edit(Product $product): Renderable
    {
        return $product->has_variants
            ? view('dashboard.product.edit-group', compact('product'))
            : view('dashboard.product.edit', compact('product'));
    }
}
