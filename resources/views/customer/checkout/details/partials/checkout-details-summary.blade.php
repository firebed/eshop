<aside id="checkout-details-summary" class="vstack p-3 bg-white border-top border rounded-3 position-relative" style="font-size: .90rem !important; border-top: 3px solid #50b64a !important">
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

    <div class="d-flex align-items-center">
        <div>{{ __('eshop::payment.' . ($order->paymentMethod->name ?? 'payment')) }}</div>
        <div class="w-6r ms-auto text-end" id="products-value">{{ format_currency($order->payment_fee) }}</div>
    </div>

    <hr class="text-secondary">

    <div class="d-flex align-items-center mb-3 fs-6 fw-bold">
        <div class="text-dark">{{ __('Total') }}</div>
        <div class="w-6r ms-auto text-end">{{ format_currency($order->total) }}</div>
    </div>

    <button x-bind:disabled="submitting" type="submit" class="btn btn-green" @unless($has_shipping_methods) disabled @endunless>
        {{ __('Payment details') }} <em class="fas fa-arrow-right ms-3"></em>
    </button>
</aside>
