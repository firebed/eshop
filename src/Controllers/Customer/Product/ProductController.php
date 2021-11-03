<?php

namespace Eshop\Controllers\Customer\Product;

use Eshop\Controllers\Customer\Controller;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Eshop\Repository\Contracts\Order;
use Illuminate\Contracts\Support\Renderable;

class ProductController extends Controller
{
    public function show(string $locale, Category $category, Product $product, Order $order): Renderable
    {
        if (!$category->visible || !$product->visible || ($product->isVariant() && !$product->parent->visible)) {
            abort(404);
        }

        $quantity = 0;
        if ($product->has_variants) {
            $product->load(['variants' => fn($q) => $q->visible()->with('parent', 'image', 'options')]);
            $variants = $product->variants->sortBy('option_values', SORT_NATURAL | SORT_FLAG_CASE);
        } else {
            $quantity = $order->getProductQuantity($product);
        }

        if ($quantity > 0) {
            session()->flash('quantity', __('The product is already in the shopping cart.'));
        }

        return $this->view(!$product->isVariant() ? 'product.show' : 'product.show-variant', [
            'category'   => $category,
            'product'    => $product,
            'variants'   => $variants ?? null,
            'images'     => $product->images('gallery')->get(),
            'quantity'   => $quantity,
            'properties' => $product->properties()->visible()->with('translation')->get()->unique(),
            'choices'    => $product->choices()->with('translation')->get(),
        ]);
    }
}
