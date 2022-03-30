<?php

namespace Eshop\Controllers\Customer\Product;

use Eshop\Controllers\Customer\Controller;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Eshop\Repository\Contracts\Order;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;

class ProductController extends Controller
{
    public function show(string $locale, Category $category, Product $product, Order $order): Renderable
    {
        // Return 404 if the category or the variant's parent is hidden
        abort_unless($category->visible || ($product->isVariant() && $product->parent->visible), 404);

        // Early return if the product is hidden and the auth user can manage the product
        // This way the user will have a special option to make the product visible again
        if (!$product->visible && auth()->user()?->can('Manage products')) {
            return $this->view('product.show-hidden', compact('category', 'product'));
        }

        abort_unless($product->visible, 404);

        $quantity = 0;
        if ($product->has_variants) {
            $product->load(['variants' => fn($q) => $q->visible()->with('parent', 'image', 'options.translation')]);
            (new Collection($product->variants->pluck('options')->collapse()->pluck('pivot')))->load('translation');
            $variants = $product->variants->sortBy('variant_values', SORT_NATURAL | SORT_FLAG_CASE);
        } else {
            $quantity = $order->getProductQuantity($product);
        }

        if ($quantity > 0) {
            session()->flash('quantity', __('The product is already in the shopping cart.'));
        }

        return $this->view($product->isVariant() ? 'product.show-variant' : 'product.show', [
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
