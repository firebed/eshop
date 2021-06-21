<table style="width: 100%; background-color: whitesmoke;">
    <tr>
        <td class="pb-2">
            <div><small>{{ __('Street') }}</small></div>
            <div>{{ $cart->shippingAddress->street }}</div>
        </td>
    </tr>
    <tr>
        <td>
            <div><small>{{ __('City') }}</small></div>
            <div>{{ $cart->shippingAddress->city }} {{ $cart->shippingAddress->postcode }}, {{ $cart->shippingAddress->country->name }}</div>
        </td>
    </tr>
</table>
