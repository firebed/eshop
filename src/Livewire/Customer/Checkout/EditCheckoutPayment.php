<?php


namespace Eshop\Livewire\Customer\Checkout;


use Eshop\Livewire\Customer\Checkout\Concerns\ControlsOrder;
use Eshop\Livewire\Customer\Checkout\Concerns\PayPalCheckout;
use Eshop\Livewire\Customer\Checkout\Concerns\StripeCheckout;
use Eshop\Repository\Contracts\Order;
use Firebed\Components\Livewire\Traits\SendsNotifications;
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

    public array  $products = [];
    public float  $userTotal;
    public string $shipping_method_id;
    public string $payment_method_id;

    public function mount(Order $order): void
    {
        if ($order->isNotEmpty() && DB::transaction(fn() => $this->softRefreshOrder($order))) {
            session()->flash('products-values-changed');
        }

        $this->shipping_method_id = $order->shipping_method_id;
        $this->payment_method_id = $order->payment_method_id;
        $this->userTotal = $order->total;
    }

    public function orderIsSubmitted(): bool
    {
        $order = app(Order::class);

        if (!$order->exists || $order->isSubmitted()) {
            $this->redirectRoute('checkout.products.index', app()->getLocale());
            return true;
        }

        return false;
    }

    public function updatedShippingMethodId($id): void
    {
        if ($this->orderIsSubmitted()) {
            return;
        }

        $order = app(Order::class);
        $order->updateShippingFee($id);
        $order->updateTotal();
        $order->save();

        $this->userTotal = $order->total;
    }

    public function updatedPaymentMethodId($id): void
    {
        if ($this->orderIsSubmitted()) {
            return;
        }

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
        if ($this->orderIsSubmitted()) {
            return NULL;
        }

        if ($this->isCartDirty($order)) {
            return NULL;
        }

        if ($order->paymentMethod->isCreditCard()) {
            $this->payWithStripe();
            return NULL;
        }

        if ($order->paymentMethod->isPayPal()) {
            return $this->payWithPayPal($order);
        }

        $this->submit($order);
        return NULL;
    }

    protected function submit(Order $order, string $payment_id = NULL): void
    {
        DB::transaction(function () use ($order, $payment_id) {
            $order->payment_id = $payment_id;
            $order->submit();
            $expires = now()->addMinutes(5);
            $this->redirect(URL::temporarySignedRoute('checkout.completed', $expires, [app()->getLocale(), $order->id]));
        });
    }

    public function render(Order $order): Renderable|null
    {
        if ($this->orderIsSubmitted()) {
            return null;
        }

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
                'trademark' => $product->trademark,
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
