<?php


namespace Eshop\Livewire\Customer\Product;


use Eshop\Livewire\Customer\Checkout\Concerns\ControlsOrder;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Eshop\Repository\Contracts\Order;
use Firebed\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class ProductVariants extends Component
{
    use ControlsOrder, SendsNotifications;

    public int $productId;
    public string $categorySlug;

    public function mount(Product $product, Category $category): void
    {
        $this->productId = $product->id;
        $this->categorySlug = $category->slug;
    }

    public function addToCart(Order $order, Product $product): void
    {
        $this->addProduct($order, $product, 1);

        $toast = view('com::customer.product.partials.product-toast', compact('product'))->render();
        $this->showSuccessToast($product->name, $toast);
        $this->emit('setCartItemsCount', $order->products->count());
        $this->skipRender();
    }

    public function render(): Renderable
    {
        $variants = Product::where('parent_id', $this->productId)
            ->visible()
            ->with('image', 'options')
            ->get()
            ->sortBy(['sku', 'variant_values'], SORT_NATURAL | SORT_FLAG_CASE);

        return view('com::customer.product.partials.wire-product-variants', [
            'variants' => $variants
        ]);
    }
}
