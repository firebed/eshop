<table class="table mb-3" style="width: 100%">
    <thead>
    <tr>
        <th style="text-align: left; padding-right: 20px">{{ __("Image") }}</th>
        <th style="text-align: left">{{ __("Product") }}</th>
        <th style="text-align: center">{{ __("Quantity") }}</th>
        <th style="text-align: right">{{ __("Price") }}</th>
        <th style="text-align: right">{{ __("Total") }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($cart->products as $product)
        <tr>
            <td style="text-align: left">@if($src = $product->image?->url('sm')) <img alt="{{ $product->name }}" src="{{ asset($src) }}" style="max-width: 60px"> @endif</td>
            <td style="text-align: left">
                @if($product->isVariant())
                    <div class="vstack">
                        <div class="fw-500">{{ $product->parent->name }}</div>
                        <small class="text-secondary">{{ $product->option_values }}</small>
                    </div>
                @else
                    <div class="fw-500">{{ $product->trademark }}</div>
                @endif
            </td>
            <td style="text-align: center">{{ format_number($product->pivot->quantity) }}</td>
            <td style="text-align: right">{{ format_currency($product->pivot->netValue) }}</td>
            <td style="text-align: right">{{ format_currency($product->pivot->total) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="4" style="text-align: right;">@isset($cart->shippingMethod) {{ __("eshop::shipping.{$cart->shippingMethod->name}") }} @else {{ "-" }} @endisset</td>
        <td style="text-align: right">{{ format_currency($cart->shipping_fee) }}</td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: right;">@isset($cart->paymentMethod) {{ __("eshop::payment.{$cart->paymentMethod->name}") }} @else {{ "-" }} @endisset</td>
        <td style="text-align: right">{{ format_currency($cart->payment_fee) }}</td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: right;"><strong>{{ __("Total") }}</strong></td>
        <td style="text-align: right"><strong>{{ format_currency($cart->total) }}</strong></td>
    </tr>
    </tfoot>
</table>
