<div class="vstack gap-1 text-secondary">
    <div class="text-dark fw-500 mb-2">{{ __("Invoice information") }}</div>
    <div>{{ $order->billingAddress->full_name }}</div>
    <div>{{ $order->billingAddress->full_street }}</div>
    <div>{{ $order->billingAddress->city }}, {{ $order->billingAddress->postcode }}</div>
    <div>{{ __("Province") }}: {{ $order->shippingAddress->province }}</div>
    <div>{{ $order->billingAddress->country->name }}</div>
</div>
