<?php

namespace Eshop\Controllers\Customer\Product;

use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Eshop\Repository\Contracts\Order;
use Eshop\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class ProductController extends Controller
{
    public function show(string $locale, Category $category, Product $product, Order $order): Renderable
    {
        if ($product->isVariant()) {
            $product->load(['parent.translation']);

            $parent = $product->parent;
            $properties = $parent->properties()->with('translation')->get()->unique();
            $choices = $parent->choices()->with('translation')->get();
        } else {
            $product->loadCount(['variants' => fn($q) => $q->visible()]);

            $properties = $product->properties()->with('translation')->get()->unique();
            $choices = $product->choices()->with('translation')->get();
        }

        $quantity = $order->getProductQuantity($product);

        if ($quantity > 0) {
            session()->flash('quantity', __('The product is already in the shopping cart.'));
        }

        return view('eshop::customer.product.show', compact('category', 'product', 'quantity', 'properties', 'choices'));
    }
}
