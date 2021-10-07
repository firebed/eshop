<x-bs::table hover>
    <thead>
    <tr>
        <td>#</td>
        <td>{{ __('Status') }}</td>
        <td>{{ __('Invoice') }}</td>
        <td>{{ __('Shipping') }}</td>
        <td>{{ __('Payment') }}</td>
        <td class="text-end">{{ __('Total') }}</td>
        <x-bs::table.heading class="text-end">{{ __('Date') }}</x-bs::table.heading>
    </tr>
    </thead>

    <tbody>
    @foreach($carts as $cart)
        <tr>
            <td><a href="{{ route('carts.show', $cart) }}" class="text-decoration-none text-dark d-block">{{ $cart->id }}</a></td>
            <td>
                @isset($cart->status)
                    <a href="{{ route('carts.show', $cart) }}" class="text-decoration-none text-dark">
                        <x-bs::badge type="{{ $cart->status->color }}" class="w-100 fw-normal">{{ __("eshop::cart.status.action." . $cart->status->name) }}</x-bs::badge>
                    </a>
                @endisset
            </td>
            <td>
                @if($cart->isDocumentInvoice())
                    <a href="{{ route('carts.show', $cart) }}" class="text-decoration-none text-dark ">
                        <x-bs::badge type="danger">ΤΙΜ</x-bs::badge>
                    </a>
                @endif
            </td>
            <td>
                @isset($cart->shippingMethod)
                    <a href="{{ route('carts.show', $cart) }}" class="text-decoration-none text-dark d-block">
                        {{ __("eshop::shipping.abbr." . $cart->shippingMethod->name) }}
                    </a>
                @endisset
            </td>
            
            <td>
                @isset($cart->paymentMethod)
                    <a href="{{ route('carts.show', $cart) }}" class="text-decoration-none text-dark d-block">
                        {{ __("eshop::payment.abbr." . $cart->paymentMethod->name) }}
                    </a>
                @endisset
            </td>
            
            <td class="text-end"><a href="{{ route('carts.show', $cart) }}" class="text-decoration-none text-dark d-block">{{ format_currency($cart->total) }}</a></td>
            <td class="text-end"><a href="{{ route('carts.show', $cart) }}" class="text-decoration-none text-dark d-block">{{ $cart->created_at->format('d/m/Y') }}</a></td>
        </tr>
    @endforeach
    </tbody>

    <caption>
        <x-eshop::pagination :paginator="$carts"/>
    </caption>
</x-bs::table>
