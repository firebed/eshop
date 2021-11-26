<div class="vstack gap-1 text-secondary">
    <div class="text-dark fw-500 mb-2">{{ __("Shipping address") }}</div>
    @if($order->shippingAddress)
        <div>{{ $order->shippingAddress->full_name }}</div>
        <div>{{ $order->shippingAddress->full_street }}</div>
        <div>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->postcode }}</div>
        <div>{{ __("Province") }}: {{ $order->shippingAddress->province }}</div>
        <div>{{ $order->shippingAddress->country->name }}</div>
    @endif
</div>
