<x-bs::navbar expand="xxl" class="card shadow-sm flex-xxl-wrap">
    <x-bs::navbar.brand class="d-xxl-flex justify-content-xxl-between w-xxl-100 py-0">
        <span>{{ __('Cart') }}</span>
        <x-bs::button.link class="d-none d-xxl-block p-0" wire:click="edit">{{ __("Edit") }}</x-bs::button.link>
    </x-bs::navbar.brand>

    <x-bs::navbar.toggler target="cart-info"/>

    <x-bs::navbar.collapse id="cart-info">
        <div class="d-grid flex-grow-1 gap-1 mt-3">
            <x-bs::group label="{{ __('Document') }}" inline>
                <x-bs::badge :type="$cart->document_type === 'Invoice' ? 'danger' : 'blue'">{{ __($cart->document_type) }}</x-bs::badge>
            </x-bs::group>

            <x-bs::group label="{{ __('Shipping') }}" inline>
                {{ $shippingMethod->name ?? '' }} ({{ format_currency($cart->shipping_fee) }})
            </x-bs::group>

            <x-bs::group label="{{ __('Payment') }}" inline>
                {{ $paymentMethod->name ?? '' }} ({{ format_currency($cart->payment_fee) }})
            </x-bs::group>

            <x-bs::group label="{{ __('Weight') }}" inline>
                {{ format_weight($cart->parcel_weight) }}
            </x-bs::group>

            <x-bs::group label="{{ __('Total quantity') }}" inline>
                {{ format_number($cart->total_quantity) }}
            </x-bs::group>

            <x-bs::group label="{{ __('Items total') }}" inline>
                {{ format_currency($cart->total - $cart->total_fees) }}
            </x-bs::group>

            <x-bs::group label="{{ __('Fees') }}" inline>
                {{ format_currency($cart->total_fees) }}
            </x-bs::group>

            <x-bs::group label="{{ __('Total') }}" inline class="fw-bold">
                <div class="col">{{ format_currency($cart->total) }}</div>
            </x-bs::group>
        </div>

        @include('eshop::dashboard.cart.partials.show.cart-overview-modal')
    </x-bs::navbar.collapse>
</x-bs::navbar>
