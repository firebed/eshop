<x-bs::table hover style="table-layout: fixed">
    <thead>
    <tr>
        <th style="width: 4rem"></th>
        <th style="width: 7rem">Παραγγελία</th>
        <th>Παραλήπτης</th>
        <th>Διεύθυνση</th>
        <th style="width: 8rem">Τηλέφωνο</th>
        <th style="width: 8rem">Κατάστημα</th>
        <th style="width: 5rem">Τύπος</th>
        <th style="width: 5rem">Βάρος</th>
        <th class="text-end" style="width: 8rem">Αντικαταβολή</th>
        <th class="text-end" style="width: 4rem"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($carts as $cart_id => $cart)
        <tr x-data="{ 
                loading: false,
                success: false,
                load: function() {
                    this.loading = true
                    axios.post(@js(route('vouchers.search-stations')), {
                        street:    @js($cart->shippingAddress->street),
                        street_no: @js($cart->shippingAddress->street_no),
                        postcode:  @js($cart->shippingAddress->postcode),
                    }).then(r => {
                        this.success = true
                        $refs.station.innerText = r.data.id
                        $refs.type.innerText = r.data.type
                    })
                    .catch(err => {})
                    .finally(() => this.loading = false)                            
                },
             }"
            class="my-1"
            :class="{ 'table-danger': !success && !loading }"
            x-on:set-station.window="
                if ($event.detail.cart == @js($cart_id)) {
                    success = true
                    $refs.station.innerText = $event.detail.id
                    $refs.type.innerText = $event.detail.type
                }
           "
            x-init="load()">
            <td class="align-middle">
                <div x-show="loading" x-cloak class="spinner-border spinner-border-sm text-gray-500" role="status"></div>
                <em x-show="!loading && success" x-cloak class="fa fa-check-circle text-success"></em>
                <em x-show="!loading && !success" x-cloak class="fa fa-times-circle text-danger"></em>
            </td>
            <td class="align-middle">{{ $cart_id }}</td>
            <td class="align-middle">{{ $cart->shippingAddress->fullname }}</td>
            <td class="align-middle text-truncate">{{ $cart->shippingAddress->fullStreet . ' ' . $cart->shippingAddress->city_or_country }}</td>
            <td class="align-middle">{{ $cart->shippingAddress->phone }}</td>
            <td class="align-middle">
                <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#search-area" data-cart="{{ $cart_id }}" data-postcode="{{ $cart->shippingAddress->postcode }}">
                    <i class="fas fa-search"></i>
                    <span x-ref="station">-</span>
                </button>
            </td>

            <td x-ref="type" class="align-middle">-</td>

            <td class="align-middle">{{ max($cart->parcel_weight / 1000, 0.5) }}</td>
            <td class="align-middle text-end">
                @if($cart->paymentMethod->isPayOnDelivery())
                    {{ $cart->total }}
                @else
                    -
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