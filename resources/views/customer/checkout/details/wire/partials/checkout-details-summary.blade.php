<div wire:loading.class="opacity-75" class="d-grid p-3 bg-white rounded border-top border-primary border-3 position-relative">
    <div wire:loading class="position-absolute start-50 top-50 translate-middle">
        <em class="fa fa-fan fa-spin text-primary fs-5"></em>
    </div>

    <h2 class="fs-5 mb-3">{{ __('Cart summary') }}</h2>

    <div class="d-grid text-gray-700">
        <div data-simplebar data-simplebar-auto-hide="false" style="max-height: 200px">
            <div class="table-responsive">
                <table class="table table-borderless table-sm small mb-0">
                    @foreach($order->products as $product)
                        <tr>
                            <td class="ps-0">{{ $product->trademark ?? '' }}</td>
                            <td class="text-end">{{ $product->pivot->quantity }}&nbsp;x</td>
                            <td class="text-end pe-0">{{ format_currency($product->pivot->netValue) }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

    <hr class="text-secondary">

    @if($shippingMethods->isEmpty())
        <div class="text-danger fw-500" wire:key="shipping-error">
            <em class="fas fa-exclamation-circle me-2"></em>{{ __('Sorry, currently we do not ship to') }} {{ $order->shippingAddress->city_or_country }}
        </div>

        <hr class="text-secondary">
    @endif

    <div class="d-flex align-items-center">
        <div>{{ __('Products value') }}</div>
        <div class="w-6r ms-auto text-end">{{ format_currency($order->products_value) }}</div>
    </div>

    <div class="d-flex align-items-center">
        <div>{{ __('Shipping fee') }}</div>
        <div class="w-6r ms-auto text-end" id="products-value">{{ format_currency($order->shipping_fee) }}</div>
    </div>

    <div class="d-flex align-items-center">
        <div>{{ __($order->paymentMethod->name ?? 'Payment fee') }}</div>
        <div class="w-6r ms-auto text-end" id="products-value">{{ format_currency($order->payment_fee) }}</div>
    </div>

    <hr class="text-secondary">

    <div class="d-flex align-items-center mb-3 fs-5 fw-bold">
        <div class="text-dark">{{ __('Total') }}</div>
        <div class="w-6r ms-auto text-end">{{ format_currency($order->total) }}</div>
    </div>

    <button type="submit" class="btn btn-primary" @if($shippingMethods->isEmpty()) disabled @endif>
        {{ __('Payment details') }} <em class="fas fa-arrow-right ms-3"></em>
    </button>
</div>
