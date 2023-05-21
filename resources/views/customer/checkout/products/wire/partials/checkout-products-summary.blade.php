<div class="d-grid p-3 bg-white rounded-3 border" id="cart-summary" style="font-size: .90rem !important; border-top: 3px solid #50b64a !important">
    <h2 class="fs-5 mb-4">{{ __('Cart summary') }}</h2>

    <div class="d-grid gap-2 text-gray-700">
        <div class="d-flex align-items-start">
            <div class="text-secondary">{{ __('Products value') }}</div>
            <div class="w-6r ms-auto text-end">{{ format_currency($order->products_value) }}</div>
        </div>

        @if($order->shippingAddress)
            <div wire:key="shipping-fee" class="d-flex align-items-start">
                <div class="text-secondary">{{ __('Shipping fee for') }} <span class="text-blue-500">{{ $order->shippingAddress->city_or_country }}</span></div>

                <div class="w-6r ms-auto text-end d-grid">
                    <span>{{ format_currency($order->shipping_fee) }}</span>
                    @if($defaultShippingFee && $order->shipping_fee < $defaultShippingFee)
                        <s class="text-danger lh-sm">{{ format_currency($defaultShippingFee) }}</s>
                    @endif
                </div>
            </div>
        @endif

        @if($order->paymentMethod && $order->payment_fee > 0)
            <div wire:key="payment-fee" class="d-flex align-items-start">
                <div class="text-secondary">{{ __('eshop::payment.' . $order->paymentMethod->name) }}</div>
                <div class="w-6r ms-auto text-end" id="payment-fee">{{ format_currency($order->payment_fee) }}</div>
            </div>
        @endif

        <hr class="text-secondary my-2">

        @if($defaultShippingFee && $order->shipping_fee < $defaultShippingFee)
            <div wire:key="active-discounts" class="text-primary d-flex align-items-center">
                <em class="fas fa-check-circle me-2"></em> @choice('eshop::order.shipping_discount', $order->shipping_fee)
            </div>
        @endif

        @if($nextShippingDiscount)
            <div wire:key="next-discounts" class="text-secondary">
                @choice('eshop::order.shipping_discount_until', $nextShippingDiscount->fee, ['value' => format_currency($nextShippingDiscount->cart_total - $order->products_value)])
            </div>
            <x-bs::progress :value="$order->products_value/$nextShippingDiscount->cart_total*100" height=".7rem"/>
        @endif

        @if($nextShippingDiscount !== null || ($defaultShipping && $order->shipping_fee > $defaultShipping->fee))
            <hr wire:key="hr" class="text-secondary my-2">
        @endif
    </div>

    <div class="vstack gap-3 py-2">
        <div class="d-flex align-items-center fw-bold fs-6">
            <div>{{ __('Total') }}</div>
            <div class="w-6r ms-auto text-end" id="order-total">{{ format_currency($order->total) }}</div>
        </div>

        @if($errors->any())
            <div wire:key="error">
                <a href="#" class="btn disabled btn-green d-block">{{ __('Checkout') }} <em class="fas fa-chevron-right ms-3"></em></a>
            </div>
        @else
            <div wire:key="checkout">
                @guest
                    <a wire:loading.class="disabled" data-bs-toggle="modal" data-bs-target="#login-modal" href="#" class="btn btn-green d-block">{{ __('Checkout') }} <em class="fas fa-chevron-right ms-3"></em></a>
                @else
                    <a wire:loading.class="disabled" href="{{ route('checkout.details.edit', app()->getLocale()) }}" class="btn btn-green d-block">{{ __('Checkout') }} <em class="fas fa-chevron-right ms-3"></em></a>
                @endguest
            </div>
        @endif
    </div>

    <hr class="text-secondary">

    <div class="hstack gap-3 align-items-baseline py-2">
        <div><em class="fas fa-shield-alt text-alpha fs-4"></em></div>
        <div class="vstack gap-1">
            <div>{{ __("Shopping security") }}</div>
            <div class="small text-secondary">{{ __("We guarantee you will receive your order or get your money back.") }}</div>
        </div>
    </div>
</div>
