<aside id="checkout-payment-summary" class="vstack bg-white p-3 border rounded-3 position-relative" style="font-size: .90rem !important; border-top: 3px solid {{ $order->paymentMethod?->isPayPal() ? "#ffc439" : "#50b64a" }} !important">
    <h2 class="fs-5 mb-3">{{ __('Cart summary') }}</h2>

    <div class="table-responsive scrollbar" style="max-height: 200px">
        <table class="table table-borderless table-sm mb-0">
            @foreach($products as $product)
                <tr>
                    <td class="ps-0">
                        <div class="vstack">
                            @if($product->isVariant())
                                <div class="fw-500">{{ $product->parent->name ?? '' }}</div>
                                <div class="text-secondary">{{ $product->option_values ?? '' }}</div>
                            @else
                                <div>{{ $product->name ?? '' }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="text-end">{{ $product->pivot->quantity }}&nbsp;x</td>
                    <td class="text-end pe-0">{{ format_currency($product->pivot->netValue) }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <hr class="text-secondary">

    <div class="d-flex align-items-center">
        <div>{{ __('Products value') }}</div>
        <div class="w-6r ms-auto text-end">{{ format_currency($order->products_value) }}</div>
    </div>

    <div class="d-flex align-items-center">
        <div>{{ __('Shipping fee') }}</div>
        <div class="w-6r ms-auto text-end" id="products-value">{{ format_currency($order->shipping_fee) }}</div>
    </div>

    @if($order->payment_fee > 0)
        <div class="d-flex align-items-center">
            <div>{{ __('eshop::payment.' . $order->paymentMethod->name) }}</div>
            <div class="w-6r ms-auto text-end" id="products-value">{{ format_currency($order->payment_fee) }}</div>
        </div>
    @endif

    <div class="d-flex align-items-center">
        <div>{{ __('Weight') }}</div>
        <div class="w-6r ms-auto text-end" id="products-value">{{ format_weight($order->parcel_weight) }}</div>
    </div>
    
    <hr class="text-secondary">

    <div class="d-flex align-items-center fs-6 fw-500">
        <div class="fw-500 text-dark">{{ __('eshop::order.total') }}</div>
        <div class="w-6r ms-auto text-end">{{ format_currency($order->total) }}</div>
    </div>

    <hr class="text-secondary">

    <div class="vstack gap-3">
        <div class="text-secondary">{{ __("The prices include VAT.") }}</div>

        @includeWhen($order->paymentMethod?->isPayPal(), 'eshop::customer.checkout.payment.ext.paypal-checkout-button')

        @includeWhen($order->paymentMethod?->isCreditCard(), 'eshop::customer.checkout.payment.ext.stripe-checkout-button')
        
        @includeWhen($order->paymentMethod?->isCreditCardSimplify(), 'eshop::customer.checkout.payment.ext.simplify-checkout-button')

        @if($order->paymentMethod === null || (!$order->paymentMethod->isPayPal() && !$order->paymentMethod->isCreditCard() && !$order->paymentMethod->isCreditCardSimplify()))
            <button x-bind:disabled="$store.form.disabled" type="submit" class="btn btn-green" @if($order->paymentMethod === null) disabled @endif>
                <div x-cloak x-show="$store.form.disabled" class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>

                <em x-show="!$store.form.disabled" class="fas fa-check me-2"></em>

                {{ __('Complete') }}
            </button>
        @endif
    </div>
</aside>
