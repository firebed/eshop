<div class="mt-3">
    <table id="items">
        <thead>
        <tr>
            <th class="text-start" style="width: 10%">#</th>
            <th class="text-start">{{ __('Description') }}</th>
            <th class="text-end" style="width: 10%">{{ __('Quantity') }}</th>
            <th class="text-end" style="width: 10%">{{ __('Price') }}</th>
            <th class="text-end" style="width: 10%">{{ __('Discount') }}</th>
            <th class="text-end" style="width: 10%">{{ __('Total') }}</th>
        </tr>
        </thead>

        <tbody>
        @foreach($cart->products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>@if ($product->isVariant()) {{ $product->parent->name }} - @endif {{ $product->sku . ' ' . $product->name }}</td>
                <td class="text-end">{{ format_number($product->pivot->quantity) }}</td>
                <td class="text-end">{{ format_number($product->pivot->price, 2) }}</td>
                <td class="text-end">{{ $product->pivot->discount > 0 ? format_percent($product->pivot->discount) : '' }}</td>
                <td class="text-end">{{ format_number($product->pivot->total, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
        @if ($cart->shipping_charge > 0 || $cart->payment_charge > 0)
            <tfoot>
            @if ($cart->shipping_charge > 0)
                <tr class="font-weight-bold">
                    <td>&nbsp;</td>
                    <td colspan="3">{{ __($cart->shippingMethod->name) }}</td>
                    <td class="text-right">{{ format_number($cart->shipping_charge/(1 + $vat->regime), 2) }}</td>
                    <td colspan="2" class="text-center">&nbsp;</td>
                    <td class="text-right">{{ format_percent($vat->regime) }}</td>
                </tr>
            @endif
            @if ($cart->payment_charge > 0)
                <tr class="font-weight-bold">
                    <td>&nbsp;</td>
                    <td colspan="3">{{ __($cart->paymentMethod->name) }}</td>
                    <td class="text-right">{{ format_number($cart->payment_charge/(1 + $vat->regime)) }}</td>
                    <td colspan="2" class="text-center">&nbsp;</td>
                    <td class="text-right">{{ format_percent($vat->regime) }}</td>
                </tr>
            @endif
            </tfoot>
        @endif
    </table>
</div>
