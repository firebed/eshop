<?php

namespace Eshop\Controllers\Customer\Product;

use Eshop\Controllers\Controller;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Eshop\Repository\Contracts\Order;
use Illuminate\Contracts\Support\Renderable;

class ProductVariantController extends Controller
{
    public function show(string $locale, Category $category, Product $product, Product $variant, Order $order): Renderable
    {
        $variant->load(['parent.translation']);

        $quantity = $order->getProductQuantity($variant);

        if ($quantity > 0) {
            session()->flash('quantity', __('The product is already in the shopping cart.'));
        }

        return view('eshop::customer.product.show', [
            'category'   => $category,
            'parent'     => $product,
            'product'    => $variant,
            'quantity'   => $quantity,
            'properties' => $product->properties()->with('translation')->get()->unique(),
            'choices'    => $product->choices()->with('translation')->get()
        ]);
    }
}
