<x-bs::table hover style="table-layout: fixed">
    <thead>
    <tr>
        <th style="width: 8rem">#Παραγγελία</th>
        <th>Παραλήπτης</th>
        <th>Διεύθυνση</th>
        <th style="width: 10rem">Courier</th>
        <th style="width: 10rem">Voucher</th>
        <th style="width: 5rem">Βάρος</th>
        <th class="text-end" style="width: 8rem">Αντικαταβολή</th>
        <th class="text-end" style="width: 4rem"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($carts as $cart_id => $cart)
        <tr>
            <td class="align-middle"><a href="{{ route('carts.show', $cart_id) }}">{{ $cart_id }}</a></td>
            <td class="align-middle">{{ $cart->shippingAddress->fullname }}</td>
            <td class="align-middle">{{ $cart->shippingAddress->city_or_country }}</td>
            <td class="align-middle">               
                <img src="{{ asset('images/' . $cart->shippingMethod->iconSrc()) }}" alt="" class="img-fluid" style="max-height: 24px; max-width: 100px">
            </td>
            <td>{{ $cart->voucher }}</td>
            <td class="align-middle">{{ format_weight($cart->parcel_weight, false) }}</td>
            <td class="align-middle text-end">
                @if($cart->paymentMethod->isPayOnDelivery())
                    {{ format_currency($cart->total) }}
                @endif
            </td>
            <td class="align-middle">
                <div class="d-flex gap-1 justify-content-end">
                    <button
                        data-bs-toggle="modal"
                        data-bs-target="#forms-modal"
                        @click="$dispatch('show-cart', @js($cart->id))"
                        type="button"
                        class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</x-bs::table>