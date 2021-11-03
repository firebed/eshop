<?php


namespace Eshop\Livewire\Customer\Product;


use Eshop\Livewire\Customer\Checkout\Concerns\ControlsOrder;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Eshop\Repository\Contracts\Order;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class ProductVariant extends Component
{
    use ControlsOrder, SendsNotifications;

    public Category $category;
    public Product  $product;
    public null|int $quantity = 1;

    public function addToCart(Order $order): void
    {
        if (!is_numeric($this->quantity) || $this->quantity < 0) {
            $this->skipRender();
            return;
        }

        if (!$this->product->canBeBought($this->quantity)) {
            $this->showWarningDialog($this->product->trademark, __("eshop::order.max_available_stock", ['quantity' => $this->quantity, 'available' => $this->product->available_stock]));
            $this->skipRender();
            return;
        }

        $this->addProduct($order, $this->product, $this->quantity);

        $toast = view('product.partials.product-toast', ['product' => $this->product])->render();
        $this->showSuccessToast($this->product->trademark, $toast);
        $this->emit('setCartItemsCount', $order->products->count());
        $this->skipRender();
    }

    public function render(): Renderable
    {
        return view('eshop::customer.product.wire.product-variant', [
            'variant' => $this->product
        ]);
    }
}
