<div wire:loading.class="opacity-75" class="d-grid bg-white gap-3 p-3 rounded border-top border-primary border-3 position-relative">
    <div wire:loading class="position-absolute start-50 top-50 translate-middle">
        <em class="fa fa-fan fa-spin text-primary fs-5"></em>
    </div>

    <h2 class="fs-5 mb-0">{{ __('Cart summary') }}</h2>

    <div class="d-grid text-gray-700">
        <div wire:ignore
             data-simplebar data-simplebar-auto-hide="false"
             x-data="{products: $wire.entangle('products')}"
             style="max-height: 200px">
            <div class="d-grid gap-1 small">
                <template x-for="product in products" :key="product.id">
                    <div class="d-flex gap-1">
                        <div class="col" x-text="product.tradeName"></div>
                        <div class="col-auto w-3r text-end text-nowrap" x-text="product.quantity"></div>
                        <div class="col-auto w-4r text-end text-nowrap" x-text="product.netValue"></div>
                    </div>
                </template>
            </div>
        </div>

        <hr class="text-secondary">

        <div wire:key="products-value" class="d-flex align-items-center">
            <div>{{ __('Products value') }}</div>
            <div class="w-6r ms-auto text-end">{{ format_currency($order->products_value) }}</div>
        </div>

        <div wire:key="shipping-fee" class="d-flex align-items-center">
            <div>{{ __('Shipping fee') }}</div>
            <div class="w-6r ms-auto text-end" id="products-value">{{ format_currency($order->shipping_fee) }}</div>
        </div>

        @if($order->payment_fee > 0)
            <div wire:key="payment-fee" class="d-flex align-items-center">
                <div>{{ __($order->paymentMethod->name) }}</div>
                <div class="w-6r ms-auto text-end" id="products-value">{{ format_currency($order->payment_fee) }}</div>
            </div>
        @endif

        <hr class="text-secondary">

        <div class="d-flex align-items-center">
            <div class="fw-500 text-dark">{{ __('Order total') }}</div>
            <div class="w-6r ms-auto text-end fs-5 fw-500">{{ format_currency($order->total) }}</div>
        </div>
    </div>

    <div class="{{ $order->paymentMethod->isPayPal() ? "d-grid" : "d-none" }}">
        @include('customer.checkout.payment.wire.ext.paypal-checkout-button')
    </div>

    <div class="{{ $order->paymentMethod->isCreditCard() ? "d-grid" : "d-none" }}">
        @include('customer.checkout.payment.wire.ext.stripe-checkout-button')
    </div>

    <div class="{{ $order->paymentMethod->isPayPal() || $order->paymentMethod->isCreditCard() ? "d-none": "d-grid" }}">
        <button type="button" wire:click.prevent="pay" class="btn btn-primary">
            <em class="fas fa-check me-2"></em>{{ __('Complete') }}
        </button>
    </div>
</div>
