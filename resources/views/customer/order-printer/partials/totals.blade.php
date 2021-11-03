<table>
    <tr>
        <td>
            <table>
                <tr>
                    <td style="width: 30%">{{ __("Total quantity") }}</td>
                    <td>{{ format_number($cart->totalQuantity) }}</td>
                </tr>
                <tr>
                    <td>{{ __("Weight") }}</td>
                    <td>{{ format_weight($cart->parcel_weight) }}</td>
                </tr>
                @isset($cart->details)
                    <tr>
                        <td colspan="2">{{ $cart->details }}</td>
                    </tr>
                @endisset
            </table>
        </td>
        <td>
            <table>
                <tr>
                    <td class="text-end" style="width: 75%">{{ __("Total") }}:</td>
                    <td class="number">{{ format_currency($cart->totalWithoutFees) }}</td>
                </tr>

                <tr>
                    <td class="text-end">@isset($cart->shippingMethod) {{ __("eshop::shipping." . $cart->shippingMethod->name) }}: @else {{ __('Shipping') }} @endisset</td>
                    <td class="number">{{ format_currency($cart->shipping_fee) }}</td>
                </tr>

                <tr>
                    <td class="text-end">@isset($cart->paymentMethod) {{ __("eshop::payment." . $cart->paymentMethod->name) }}: @else {{ __('Payment') }} @endisset</td>
                    <td class="number">{{ format_currency($cart->payment_fee) }}</td>
                </tr>

                <tr class="fw-bold">
                    <td class="text-end">{{ __("Final total") }}:</td>
                    <td class="number">{{ format_currency($cart->total) }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
