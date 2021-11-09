<div class="vstack gap-1 text-secondary">
    <div class="text-dark fw-500 mb-2">{{ __("Invoice information") }}</div>
    @if($order->invoice && $order->invoice->billingAddress)
        <div>{{ $order->invoice->full_name }}</div>
        <div>{{ $order->invoice->billingAddress->full_street }}</div>
        <div>{{ $order->invoice->billingAddress->city }}, {{ $order->invoice->billingAddress->postcode }}</div>
        <div>{{ __("Province") }}: {{ $order->invoice->billingAddress->province }}</div>
        <div>{{ $order->invoice->billingAddress->country->name }}</div>
    @endif
</div>
