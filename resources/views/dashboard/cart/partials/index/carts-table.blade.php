<x-bs::table hover class="fs-6">
    <thead>
    <tr class="table-light">
        <td class="w-3r rounded-top">
            <x-bs::dropdown class="lh-sm">
                <button type="button" class="p-0 m-0 border-0 bg-transparent" id="select-carts-toggler" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="d-block rounded bg-white" style="height: 1em; width: 1em; border: 1px solid rgba(0,0,0,.25)"></span>
                </button>

                <x-bs::dropdown.menu button="select-carts-toggler">
                    <x-bs::dropdown.item wire:click.prevent="$set('selectAll', true)">{{ __("All") }}</x-bs::dropdown.item>
                    <x-bs::dropdown.item wire:click.prevent="$set('selected', [])">{{ __("None") }}</x-bs::dropdown.item>
                    <x-bs::dropdown.divider/>
                    @foreach($statuses as $status)
                        <x-bs::dropdown.item wire:click.prevent="selectByStatus({{ $status->id }})">{{ __("eshop::cart.status.$status->name") }}</x-bs::dropdown.item>
                    @endforeach
                    <x-bs::dropdown.divider/>
                    @foreach($shippingMethods as $method)
                        <x-bs::dropdown.item wire:click.prevent="selectByShippingMethod({{ $method->id }})">{{ $method->name }}</x-bs::dropdown.item>
                    @endforeach
                </x-bs::dropdown.menu>
            </x-bs::dropdown>
        </td>

        <td class="w-3r">#</td>
        <td class="w-3r">&nbsp;</td>
        <td class="w-7r">{{ __("Status") }}</td>
        <td>{{ __("Operators") }}</td>
        <td>{{ __("Customer") }}</td>
        <td>{{ __("Shipping") }}</td>
        <td>{{ __("Payment") }}</td>
        <td class="text-end">{{ __("Total") }}</td>
        <td class="text-end rounded-top">{{ __("Date") }}</td>
    </tr>
    </thead>

    <tbody>
    @forelse($carts as $cart)
        <tr wire:key="cart-row-{{ $cart->id }}"
            @can('Manage orders')
            @unless($cart->isViewed()) class="fw-bold" @endunless
            @elsecan('Manage assigned orders')
            @if($cart->operators->find(user()->id)->pivot->viewed_at === null) class="fw-bold" @endif
            @endcan
        >
            <td class="align-middle">
                <x-bs::input.checkbox wire:model="selected" id="cart-{{ $cart->id }}" value="{{ $cart->id }}"/>
            </td>
            <td class="align-middle"><a href="{{ route('carts.show', $cart) }}" class="text-decoration-none text-dark">#{{ $cart->id }}</a></td>
            <td class="align-middle">
                @if($cart->isDocumentInvoice())
                    <span class="badge bg-red-400 rounded-pill px-3">ΤΙΜ</span>
                @endif
            </td>
            <td class="align-middle">
                @if($cart->status)
                    <a href="{{ route('carts.show', $cart) }}" class="d-block text-decoration-none text-dark">
                        <x-bs::badge type="{{ $cart->status->color ?? '' }}" class="w-100 fw-normal">{{ __('eshop::account.order.' . $cart->status->name) ?: '' }}</x-bs::badge>
                    </a>
                @endif
            </td>
            <td class="align-middle">
                <a href="{{ route('carts.show', $cart) }}" class="d-block text-decoration-none text-dark text-nowrap">
                    {{ $cart->operators->pluck('first_name')->join(', ') }}
                </a>
            </td>
            <td class="align-middle">
                <a href="{{ route('carts.show', $cart) }}" class="d-block text-decoration-none text-dark text-nowrap">
                    <em class="fa fa-user w-1r @if($cart->user_id) text-secondary @else text-light @endif"></em>
                    <span>{{ $cart->shippingAddress->to ?? '' }}</span>
                </a>
            </td>
            <td class="align-middle">
                @if($cart->shippingMethod)
                    <div class="d-flex gap-2 align-items-center">
                        <em class="fas fa-shipping-fast {{ blank($cart->voucher) ? 'text-light' : 'text-primary' }}"></em>
                        <a href="{{ route('carts.show', $cart) }}" class="text-decoration-none align-items-center text-dark">
                            {{ __("eshop::shipping.abbr." . $cart->shippingMethod->name) }}
                        </a>
                    </div>
                @endif
            </td>
            <td class="align-middle">
                @if($cart->paymentMethod)
                    <a href="{{ route('carts.show', $cart) }}" class="text-decoration-none align-items-center text-dark">
                        {{ __('eshop::payment.abbr.' . $cart->paymentMethod->name) }}
                    </a>
                @endif
            </td>
            <td class="text-end align-middle">
                <a href="{{ route('carts.show', $cart) }}" class="d-block text-decoration-none text-dark">{{ format_currency($cart->total) }}</a>
            </td>
            <td class="text-end align-middle">
                <a href="{{ route('carts.show', $cart) }}" class="d-block text-decoration-none text-dark">{{ $cart->submitted_at->isToday() ? $cart->submitted_at->format('H:i') : $cart->submitted_at->format('d/m/y')}}</a>
            </td>
        </tr>
    @empty
        <tr wire:key="no-records-found">
            <td colspan="9" class="text-center py-4 fst-italic text-secondary">{{ __("No records found") }}</td>
        </tr>
    @endforelse
    </tbody>

    <caption>
        <x-eshop::wire-pagination :paginator="$carts"/>
    </caption>
</x-bs::table>
