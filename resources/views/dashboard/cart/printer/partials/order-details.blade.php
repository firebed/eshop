<table class="table-dense">
    <tr>
        <td class="fw-bold" colspan="2">{{ __("Order details") }}</td>
    </tr>
    <tr>
        <td class="text-secondary" style="width: 30%">{{ __('Document') }}</td>
        <td>{{ __('Order form') }}</td>
    </tr>
    <tr>
        <td class="text-secondary">{{ __('Number') }}</td>
        <td>{{ $cart->id }}</td>
    </tr>
    <tr>
        <td class="text-secondary">{{ __('Shipping') }}</td>
        <td>@isset($cart->shippingMethod) {{ __("eshop::shipping." . $cart->shippingMethod->name) }} @endisset</td>
    </tr>
    <tr>
        <td class="text-secondary">{{ __('Payment') }}</td>
        <td>@isset($cart->paymentMethod) {{ __("eshop::payment." . $cart->paymentMethod->name) }} @endisset</td>
    </tr>
    <tr>
        <td class="text-secondary">{{ __('Date') }}</td>
        <td>{{ optional($cart->submitted_at)->format('d/m/y H:i') }}</td>
    </tr>
</table>
