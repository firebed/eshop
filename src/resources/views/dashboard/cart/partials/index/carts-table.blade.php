<x-bs::table hover>
    <thead>
    <tr class="table-light">
        <td class="w-3r rounded-top">
            <x-bs::dropdown class="lh-sm">
                <button type="button" class="p-0 m-0 border-0 bg-transparent" id="select-carts-toggler" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="rounded bg-white" style="height: 1em; width: 1em; border: 1px solid rgba(0,0,0,.25)"></div>
                </button>

                <x-bs::dropdown.menu button="select-carts-toggler">
                    <x-bs::dropdown.item wire:click.prevent="$set('selectAll', true)">{{ __("All") }}</x-bs::dropdown.item>
                    <x-bs::dropdown.item wire:click.prevent="$set('selected', [])">{{ __("None") }}</x-bs::dropdown.item>
                    <x-bs::dropdown.divider/>
                    @foreach($statuses as $status)
                        <x-bs::dropdown.item wire:click.prevent="selectByStatus({{ $status->id }})">{{ $status->name }}</x-bs::dropdown.item>
                    @endforeach
                    <x-bs::dropdown.divider/>
                    @foreach($shippingMethods as $method)
                        <x-bs::dropdown.item wire:click.prevent="selectByShippingMethod({{ $method->id }})">{{ $method->name }}</x-bs::dropdown.item>
                    @endforeach
                </x-bs::dropdown.menu>
            </x-bs::dropdown>
        </td>

        <td class="w-3r">{{ __('ID') }}</td>
        <td class="w-3r">&nbsp;</td>
        <td class="w-7r">{{ __("Status") }}</td>
        <td>{{ __("Customer") }}</td>
        <td>{{ __("Shipping") }}</td>
        <td>{{ __("Payment") }}</td>
        <td class="text-end">{{ __("Total") }}</td>
        <td class="text-end rounded-top">{{ __("Date") }}</td>
    </tr>
    </thead>

    <tbody>
    @forelse($carts as $cart)
        <tr @unless($cart->isViewed()) class="fw-bold" @endunless wire:key="cart-row-{{ $cart->id }}">
            <td class="align-middle">
                <x-bs::input.checkbox wire:model="selected" id="cart-{{ $cart->id }}" value="{{ $cart->id }}"/>
            </td>
            <td class="align-middle"><a href="{{ route('carts.show', $cart) }}" class="text-decoration-none text-dark">#{{ $cart->id }}</a></td>
            <td class="align-middle">
                @if($cart->isDocumentInvoice())
                    <x-bs::badge type="danger">ΤΙΜ</x-bs::badge>
                @endif
            </td>
            <td class="align-middle">
                <x-bs::badge type="{{ $cart->status->color ?? '' }}" class="w-100 fw-normal">{{ $cart->status->name ?? '' }}</x-bs::badge>
            </td>
            <td class="align-middle">
                <a href="{{ route('carts.show', $cart) }}" class="d-block text-decoration-none text-dark">
                    <em class="fa fa-user w-1r @if($cart->user_id) text-secondary @else text-light @endif"></em>
                    <span>{{ $cart->shippingAddress->to ?? '' }}</span>
                </a>
            </td>
            <td class="align-middle">
                <a href="{{ route('carts.show', $cart) }}" class="d-block text-decoration-none text-dark">{{ __($cart->shippingMethod->name) }}</a>
            </td>
            <td class="align-middle">
                <a href="{{ route('carts.show', $cart) }}" class="d-block text-decoration-none text-dark">{{ __($cart->paymentMethod->name) }}</a>
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
            <td colspan="8" class="text-center py-4 fst-italic text-secondary">{{ __("No records found") }}</td>
        </tr>
    @endforelse
    </tbody>

    <caption>
        <x-eshop::pagination :paginator="$carts"/>
    </caption>
</x-bs::table>
