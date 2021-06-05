<?php


namespace Ecommerce\Livewire\Customer\Product;


use Ecommerce\Livewire\Customer\Checkout\Concerns\ControlsOrder;
use Ecommerce\Models\Product\Product;
use Ecommerce\Repository\Contracts\Order;
use Firebed\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class AddToCartForm extends Component
{
    use ControlsOrder, SendsNotifications;

    public Product $product;
    public $quantity;

    public function mount(Order $order): void
    {
        $this->quantity = $order->getProductQuantity($this->product) ?: 1;
    }

    public function addToCart(Order $order): void
    {
        $this->addProduct($order, $this->product, $this->quantity);

        $toast = view('com::customer.product.partials.product-toast', [
            'product' => $this->product
        ])->render();

        $this->showSuccessToast($this->product->name, $toast);
        $this->emit('setCartItemsCount', $order->products->count());
        $this->skipRender();
    }

    public function render(): Renderable
    {
        return view('com::customer.product.wire.add-to-cart-form',);
    }
}