<table class="table-dense">
    <tr>
        <td colspan="2" class="fw-bold">{{ __("Shipping address") }}</td>
    </tr>
    <tr>
        <td class="text-secondary" style="width: 30%">{{ __("Name") }}</td>
        <td>{{ $cart->shippingAddress?->full_name }}</td>
    </tr>
    <tr>
        <td class="text-secondary">{{ __("Phone") }}</td>
        <td>{{ $cart->shippingAddress?->phone }}</td>
    </tr>
    <tr>
        <td class="text-secondary" style="width: 30%">{{ __("Street") }}</td>
        <td>{{ $cart->shippingAddress?->street }}</td>
    </tr>
    <tr>
        <td class="text-secondary">{{ __("City") }}</td>
        <td>{{ $cart->shippingAddress?->city }}, {{ __("P.O") }}: {{  $cart->shippingAddress?->postcode }}</td>
    </tr>
    <tr>
        <td class="text-secondary">{{ __("Region") }}</td>
        <td>{{ $cart->shippingAddress?->province }}</td>
    </tr>
    <tr>
        <td class="text-secondary">{{ __("Country") }}</td>
        <td>{{ $cart->shippingAddress?->country->name }}</td>
    </tr>
</table>
