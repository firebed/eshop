<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Controller;
use Eshop\Models\Product\Product;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     * @throws AuthorizationException
     */
    public function index(): Renderable
    {
        return view('eshop::dashboard.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     * @throws AuthorizationException
     */
    public function create(): Renderable
    {
        return view('eshop::dashboard.product.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     * @throws AuthorizationException
     */
    public function createGroup(): Renderable
    {
        return view('eshop::dashboard.product.create-group');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return Renderable
     * @throws AuthorizationException
     */
    public function edit(Product $product): Renderable
    {
        return $product->has_variants
            ? view('eshop::dashboard.product.edit-group', compact('product'))
            : view('eshop::dashboard.product.edit', compact('product'));
    }
}
