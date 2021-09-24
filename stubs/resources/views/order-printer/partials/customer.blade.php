<table class="table-dense">
    <tr>
        <td colspan="2" class="fw-bold">{{ __("Customer details") }}</td>
    </tr>
    <tr>
        <td class="text-secondary" style="width: 30%">{{ __("Name") }}</td>
        <td>{{ $cart->contact->fullName }}</td>
    </tr>
    <tr>
        <td class="text-secondary">{{ __("Phone") }}</td>
        <td>{{ $cart->contact->phone }}</td>
    </tr>
</table>
