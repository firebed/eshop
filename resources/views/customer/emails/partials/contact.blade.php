<table style="width: 100%; padding: 10px">
    <tr>
        <td class="pb-2">
            <div><small>{{ __('Customer') }}</small></div>
            <div>{{ $cart->contact->first_name }} {{ $cart->contact->last_name }}</div>
        </td>
    </tr>
    <tr>
        <td>
            <div><small>{{ __('Phone') }}</small></div>
            <div>{{ $cart->contact->phone }}</div>
        </td>
    </tr>
</table>
