<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductTranslationController extends Controller
{
    public function edit(int $product_id): Renderable
    {
        $product = Product::findOrFail($product_id);

        return $this->view('product-translation.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->dd();
        return back();
    }
}
