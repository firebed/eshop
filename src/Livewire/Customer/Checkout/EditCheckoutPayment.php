<?php


namespace Eshop\Livewire\Customer\Checkout;


use Eshop\Livewire\Customer\Checkout\Concerns\ControlsOrder;
use Eshop\Livewire\Customer\Checkout\Concerns\PayPalCheckout;
use Eshop\Livewire\Customer\Checkout\Concerns\StripeCheckout;
use Eshop\Repository\Contracts\Order;
use Firebed\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class EditCheckoutPayment extends Component
{
    use ControlsOrder;
    use StripeCheckout;
    use PayPalCheckout;
    use SendsNotifications;

    public float $userTotal;
    public       $shipping_method_id;
    public       $payment_method_id;

    public function mount(Order $order): void
    {
        if ($order->isNotEmpty() && DB::transaction(fn() => $this->softRefreshOrder($order))) {
            session()->flash('products-values-changed');
        }

        $this->shipping_method_id = $order->shipping_method_id;
        $this->payment_method_id = $order->payment_method_id;
        $this->userTotal = $order->total;
    }

    public function updatedShippingMethodId($id): void
    {
        $order = app(Order::class);
        $order->updateShippingFee($id);
        $order->updateTotal();
        $order->save();

        $this->userTotal = $order->total;
    }

    public function updatedPaymentMethodId($id): void
    {
        $order = app(Order::class);
        $order->updatePaymentFee($id);
        $order->updateTotal();
        $order->save();

        $this->userTotal = $order->total;
    }

    protected function isCartDirty(Order $order): bool
    {
        DB::transaction(fn() => $this->softRefreshOrder($order));

        if ($order->total === $this->userTotal) {
            return FALSE;
        }

        $this->payment_method_id = $order->payment_method_id;
        $this->shipping_method_id = $order->shipping_method_id;

        $this->userTotal = $order->total;
        $this->showWarningDialog(__("Warning"), __("Your cart has changed during payment. Please checkout your order and try again."));
        return TRUE;
    }

    public function pay(Order $order): mixed
    {
        if ($this->isCartDirty($order)) {
            return null;
        }

        if ($order->paymentMethod->isCreditCard()) {
            $this->payWithStripe();
            return null;
        }

        if ($order->paymentMethod->isPayPal()) {
            return $this->payWithPayPal($order);
        }

        $this->submit($order);
        return null;
    }

    protected function submit(Order $order): void
    {
        DB::transaction(function () use ($order) {
//            $order->submit();
            $expires = now()->addMinutes(5);
            $this->redirect(URL::temporarySignedRoute('checkout.completed', $expires, [app()->getLocale(), $order->id]));
        });
    }

    public $products = [];

    public function render(Order $order): Renderable
    {
        $country = $order->shippingAddress->country;
        $shippingMethods = $country->filterShippingOptions($order->products_value);
        $paymentMethods = $country->filterPaymentMethods($order->products_value);
        Collection::make($shippingMethods)->load('translation', 'shippingMethod');
        Collection::make($paymentMethods)->load('translation', 'paymentMethod');

        $products = $order->products;
        $products->load('parent', 'options');
        $products->merge($order->products->pluck('parent')->filter())->load('translation');

        $this->products = [];
        foreach ($products as $product) {
            $this->products[] = [
                'id'        => $product->id,
                'tradeName' => $product->tradeName,
                'quantity'  => $product->pivot->quantity . ' x',
                'netValue'  => format_currency($product->pivot->netValue)
            ];
        }

        return view('eshop::customer.checkout.payment.wire.edit', [
            'order'           => $order,
            'shippingMethods' => $shippingMethods,
            'paymentMethods'  => $paymentMethods,
        ]);
    }
}
