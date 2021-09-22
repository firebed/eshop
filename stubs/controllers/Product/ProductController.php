<?php

namespace App\Http\Controllers\Product;

use Eshop\Controllers\Controller;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Eshop\Repository\Contracts\Order;
use Illuminate\Contracts\Support\Renderable;

class ProductController extends Controller
{
    public function show(string $locale, Category $category, Product $product, Order $order): Renderable
    {
        if (!($category->visible && $product->visible)) {
            abort(404);
        }

        if ($product->has_variants) {
            $product->loadCount(['variants' => fn($q) => $q->visible()]);
        }

        $quantity = 0;
        if (!$product->has_variants) {
            $quantity = $order->getProductQuantity($product);
        }

        if ($quantity > 0) {
            session()->flash('quantity', __('The product is already in the shopping cart.'));
        }

        return view('product.show', [
            'category'   => $category,
            'product'    => $product,
            'images'     => $product->images('gallery')->get(),
            'quantity'   => $quantity,
            'properties' => $product->properties()->visible()->with('translation')->get()->unique(),
            'choices'    => $product->choices()->with('translation')->get(),
        ]);
    }
}
