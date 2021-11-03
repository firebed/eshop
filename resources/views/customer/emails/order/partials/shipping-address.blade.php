<div style="margin-bottom: 1rem; background-color: whitesmoke;">
    <table style="width: 100%; padding: 1rem;">
        <tr>
            <td style="padding-bottom: 1rem">
                <div><small>{{ __('Name') }}</small></div>
                <div>{{ $cart->shippingAddress->full_name }}</div>
            </td>
        </tr>
        <tr>
            <td style="padding-bottom: 1rem">
                <div><small>{{ __('Address') }}</small></div>
                <div>{{ $cart->shippingAddress->full_street }}, {{ $cart->shippingAddress->city }} {{ $cart->shippingAddress->postcode }}@unless($cart->shippingAddress->isLocalCountry()), {{ $cart->shippingAddress->country->name }} @endunless</div>
            </td>
        </tr>
        <tr>
            <td>
                <div><small>{{ __('City') }}</small></div>
                <div>{{ $cart->shippingAddress->city }} {{ $cart->shippingAddress->postcode }}, {{ $cart->shippingAddress->country->name }}</div>
            </td>
        </tr>
    </table>
</div>