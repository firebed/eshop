<tr x-data="{ cart_id: {{ $cart->id }}, number: @entangle('number'), loading: false }"
    x-on:purchase="
        if (number.length > 0) {
            return pushStatus(cart_id)
        }
    
        loading = true
        removeStatus(cart_id)
        $wire.createVoucher().finally(() => loading = false)
     "
    x-on:voucher-created.window="
        if (event.detail.cart_id === cart_id) {
            number = event.detail.number
            pushStatus(cart_id)
        }
    "
>
    <td><a href="{{ route('carts.show', $cart->id) }}" target="_blank">#{{ $cart->id }}</a></td>

    <td>
        <em x-show="loading" x-cloak class="fa fa-spinner fa-spin fa-sm"></em>
        <em x-show="!loading && number.length > 0" x-cloak class="fa fa-check-circle text-success fa-sm"></em>
        <em x-show="!loading && number.length === 0 && status[cart_id] === false" x-cloak class="fa fa-times-circle text-danger fa-sm"></em>
        <span x-text="number"></span>
    </td>

    <td>
        <div>
            @error('courier')
            <div class="text-danger fw-500 mb-1"><em class="fa fa-exclamation-circle me-1"></em>{{ $message }}</div>
            @enderror
            <div class="fw-500">{{ $cart->shippingAddress->fullname }}</div>
            <div class="small text-secondary">{{ $cart->shippingAddress->city_or_country }}</div>
        </div>
    </td>

    <td>
        <img src="{{ asset('images/' . $cart->shippingMethod->courier()->icon()) }}" alt="" class="img-fluid" style="max-height: 20px; max-width: 80px">
    </td>

    <td class="text-end">{{ format_weight(max($cart->parcel_weight, 500), false) }}</td>
    <td class="text-end">
        @if($cart->paymentMethod->isPayOnDelivery())
            {{ format_currency($cart->total) }}
        @endif
    </td>
    <td>
        @if($cart->voucher === null)
            <div class="d-flex gap-1 justify-content-end">
                <button
                    wire:click="$emitTo('dashboard.voucher.create', 'createVoucher', {{ $cart->id }})"
                    type="button"
                    class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i>
                </button>
            </div>
        @endif
    </td>
</tr>