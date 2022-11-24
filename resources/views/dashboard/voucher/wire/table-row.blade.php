<tr x-data="{ number: @entangle('number') }" x-on:purchase="$wire.createVoucher()">
    <td class="align-middle"><a href="{{ route('carts.show', $cart->id) }}" target="_blank">#{{ $cart->id }}</a></td>
    <td x-text="number"></td>
    <td class="align-middle">{{ $cart->shippingAddress->fullname }}</td>
    <td class="align-middle">{{ $cart->shippingAddress->city_or_country }}</td>
    <td class="align-middle">
        <img src="{{ asset('images/' . $cart->shippingMethod->courier()->icon()) }}" alt="" class="img-fluid" style="max-height: 20px; max-width: 80px">
    </td>
    <td class="align-middle text-end">{{ format_weight(max($cart->parcel_weight, 500), false) }}</td>
    <td class="align-middle text-end">
        @if($cart->paymentMethod->isPayOnDelivery())
            {{ format_currency($cart->total) }}
        @endif
    </td>
    <td class="align-middle">
        <div class="d-flex gap-1 justify-content-end">
            <button
                wire:click="$emit('createVoucher', {{ $cart->id }})"
                type="button"
                class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i>
            </button>
        </div>
    </td>
</tr>