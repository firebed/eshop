<aside id="checkout-payment-summary" class="vstack bg-white p-3 border rounded-3 position-relative" style="font-size: .90rem !important; border-top: 3px solid #50b64a !important">
    <h2 class="fs-5 mb-3">{{ __('Cart summary') }}</h2>

    <div data-simplebar data-simplebar-auto-hide="false" style="max-height: 200px">
        <div class="table-responsive">
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

{{--    @if($inaccessible_area_fee > 0 || $excess_weight_fee > 0)--}}
{{--        <hr class="text-secondary">--}}
{{--        <div class="vstack gap-2 text-danger small fw-500">--}}
{{--            @if ($inaccessible_area_fee > 0)--}}
{{--                <div class="d-flex">--}}
{{--                    <div><em class="fas fa-exclamation-circle me-2"></em></div>--}}
{{--                    <div>{{ __("eshop::order.inaccessible_area_fee", ['fee' => format_currency($inaccessible_area_fee)]) }}</div>--}}
{{--                </div>--}}
{{--            @endif--}}

{{--            @if ($excess_weight_fee > 0)--}}
{{--                <div class="d-flex">--}}
{{--                    <div><em class="fas fa-exclamation-circle me-2"></em></div>--}}
{{--                    <div>{{ __("eshop::order.excess_weight_fee", ['fee' => format_currency($excess_weight_fee), 'weight' => format_weight($weight_limit->weight_limit)]) }}</div>--}}
{{--                </div>--}}
{{--            @endif--}}
{{--        </div>--}}
{{--    @endif--}}

    <hr class="text-secondary">

    <div class="d-flex align-items-center fs-6 fw-500">
        <div class="fw-500 text-dark">{{ __('eshop::order.total') }}</div>
        <div class="w-6r ms-auto text-end">{{ format_currency($order->total) }}</div>
    </div>

    <hr class="text-secondary">

    <div class="vstack gap-3">
        <div class="text-secondary">Στις τιμές συμπεριλαμβάνεται Φ.Π.Α.</div>

        @includeWhen($order->paymentMethod?->isPayPal(), 'checkout.payment.ext.paypal-checkout-button')

        @includeWhen($order->paymentMethod?->isCreditCard(), 'checkout.payment.ext.stripe-checkout-button')

        @if($order->paymentMethod === null || (!$order->paymentMethod->isPayPal() && !$order->paymentMethod->isCreditCard()))
            <button type="submit" class="btn btn-green" @if($order->paymentMethod === null) disabled @endif>
                <em class="fas fa-check me-2"></em>{{ __('Complete') }}
            </button>
        @endif
    </div>
</aside>
