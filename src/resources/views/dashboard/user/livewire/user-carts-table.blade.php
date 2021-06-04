<x-bs::table hover>
    <thead>
    <tr>
        <td>{{ __('ID') }}</td>
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
                        <x-bs::badge type="{{ $cart->status->color }}" class="w-100 fw-normal">{{ $cart->status->name }}</x-bs::badge>
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
            <td><a href="{{ route('carts.show', $cart) }}" class="text-decoration-none text-dark d-block">{{ $cart->shippingMethod->name ?? '' }}</a></td>
            <td><a href="{{ route('carts.show', $cart) }}" class="text-decoration-none text-dark d-block">{{ $cart->paymentMethod->name ?? '' }}</a></td>
            <td class="text-end"><a href="{{ route('carts.show', $cart) }}" class="text-decoration-none text-dark d-block">{{ format_currency($cart->total) }}</a></td>
            <td class="text-end"><a href="{{ route('carts.show', $cart) }}" class="text-decoration-none text-dark d-block">{{ $cart->created_at->format('d/m/Y') }}</a></td>
        </tr>
    @endforeach
    </tbody>

    <caption>
        <div class="d-flex justify-content-between align-items-center">
            <div class="small">{{ __('pagination.showing', ['first' => $carts->firstItem() ?? 0, 'last' => $carts->lastItem() ?? 0, 'total' => $carts->total()]) }}</div>

            {{ $carts->onEachSide(1)->links() }}
        </div>
    </caption>
</x-bs::table>
