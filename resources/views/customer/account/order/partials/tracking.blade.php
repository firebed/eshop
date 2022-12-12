<div class="vstack gap-1">
    <small class="text-secondary">{{ __("To monitor the shipment of the parcel please click on the button below.") }}</small>
    <a class="btn btn-primary align-self-center" href="{{ $order->shippingMethod?->getVoucherUrl($order->voucher->number) }}" target="_blank"><em class="fas fa-shipping-fast me-2"></em>{{ $order->voucher->number }} ({{ __("eshop::shipping." . $order->shippingMethod->name) }})</a>
</div>
