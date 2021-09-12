<?php


namespace Eshop\Livewire\Customer\Product;


use Eshop\Livewire\Customer\Checkout\Concerns\ControlsOrder;
use Eshop\Models\Product\Product;
use Eshop\Repository\Contracts\Order;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class AddToCartForm extends Component
{
    use ControlsOrder, SendsNotifications;

    public Product $product;
    public int     $quantity;

    public function mount(Order $order): void
    {
        $this->quantity = $order->getProductQuantity($this->product) ?: 1;
    }

    public function addToCart(Order $order): void
    {
        if ($this->product === null) {
            $this->showWarningDialog("Παρακαλώ επιλέξτε παραλλαγή");
            $this->skipRender();
            return;
        }

        if (!$this->product->canBeBought($this->quantity)) {
            $this->showWarningDialog($this->product->trademark, __("eshop::order.max_available_stock", ['quantity' => $this->quantity, 'available' => $this->product->available_stock]));
            $this->skipRender();
            return;
        }

        if (!$this->addProduct($order, $this->product, $this->quantity)) {
            return;
        }

        $toast = view('eshop::customer.product.partials.product-toast', [
            'product' => $this->product
        ])->render();

        $this->showSuccessToast($this->product->trademark, $toast);
        $this->emit('setCartItemsCount', $order->products->count());
        $this->skipRender();
    }

    public function render(): Renderable
    {
        return view('eshop::customer.product.wire.add-to-cart-form');
    }
}
