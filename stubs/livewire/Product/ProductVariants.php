<?php


namespace App\Http\Livewire\Product;


use App\Http\Livewire\Checkout\Concerns\ControlsOrder;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Eshop\Repository\Contracts\Order;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class ProductVariants extends Component
{
    use ControlsOrder, SendsNotifications;

    public Category $category;
    public Product  $product;

    public function addToCart(Order $order, Product $product, $quantity = 1): void
    {
        if (!$product->canBeBought($quantity)) {
            $this->showWarningDialog($product->trademark, __("Unfortunately there are not $quantity pieces of this product. Available stock: " . $product->available_stock));
            $this->skipRender();
            return;
        }

        $this->addProduct($order, $product, $quantity);

        $toast = view('product.partials.product-toast', compact('product'))->render();
        $this->showSuccessToast($product->trademark, $toast);
        $this->emit('setCartItemsCount', $order->products->count());
    }

    public function render(): Renderable
    {
        $variants = $this->product
            ->variants()
            ->visible()
            ->with('parent', 'image', 'options')
            ->get()
            ->sortBy(['sku', 'variant_values'], SORT_NATURAL | SORT_FLAG_CASE);

        return view('product.wire.product-variants', [
            'variants' => $variants
        ]);
    }
}
