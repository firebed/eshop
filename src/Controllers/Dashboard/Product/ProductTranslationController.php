<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;

class ProductTranslationController extends Controller
{
    public function __invoke(int $product_id): Renderable
    {
        $product = Product::findOrFail($product_id);

        return $this->view('product-translation.edit', compact('product'));
    }
}
