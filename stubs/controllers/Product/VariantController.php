<?php

namespace App\Http\Controllers\Product;

use Eshop\Controllers\Controller;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Eshop\Repository\Contracts\Order;
use Illuminate\Contracts\Support\Renderable;

class VariantController extends Controller
{
    public function show(string $locale, Category $category, Product $product, Product $variant, Order $order): Renderable
    {
        if (!($category->visible && $product->visible && $variant->visible)) {
            abort(404);
        }

        $variant->load(['parent.translation']);

        $quantity = $order->getProductQuantity($variant);

        if ($quantity > 0) {
            session()->flash('quantity', __('The product is already in the shopping cart.'));
        }

        return view('product-variant.show', [
            'category'   => $category,
            'parent'     => $product,
            'product'    => $variant,
            'quantity'   => $quantity,
            'properties' => $product->properties()->with('translation')->get()->unique(),
            'choices'    => $product->choices()->with('translation')->get(),
        ]);
    }
}
