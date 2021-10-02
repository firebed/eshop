<div class="vstack gap-1 text-secondary">
    <div class="text-dark fw-500 mb-2">{{ __("eshop::account.order.info") }}</div>
    <x-bs::group :label="__('Date')" inline>
        <span class="text-dark">{{ $order->submitted_at->isoFormat('llll') }}</span>
    </x-bs::group>

    <x-bs::group :label="__('Status')" inline>
        <span class="text-dark">{{ __('eshop::cart.status.action.' . $order->status->name) }}</span>
    </x-bs::group>

    <x-bs::group :label="__('Shipping')" inline>
        <span class="text-dark">{{ __("eshop::shipping.{$order->shippingMethod->name}") ?? '' }}</span>
    </x-bs::group>

    <x-bs::group :label="__('Shipping fee')" inline>
        <span class="text-dark">{{ format_currency($order->shipping_fee) }}</span>
    </x-bs::group>

    <x-bs::group :label="__('Payment')" inline>
        <span class="text-dark">{{ __("eshop::payment.{$order->paymentMethod->name}") ?? '' }}</span>
    </x-bs::group>

    <x-bs::group :label="__('Payment fee')" inline>
        <span class="text-dark">{{ format_currency($order->payment_fee) }}</span>
    </x-bs::group>

    <x-bs::group :label="__('Total')" inline class="fw-500">
        <span class="text-dark">{{ format_currency($order->total) }}</span>
    </x-bs::group>
</div>
