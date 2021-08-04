<x-bs::navbar expand="xxl" class="card shadow-sm flex-xxl-wrap">
    <x-bs::navbar.brand class="d-xxl-flex justify-content-xxl-between w-xxl-100 py-0">
        <span>{{ __('Shipping Address') }}</span>
        <x-bs::button.link class="d-none d-xxl-block p-0" wire:click="edit">{{ __("Edit") }}</x-bs::button.link>
    </x-bs::navbar.brand>

    <x-bs::navbar.toggler target="shipping-address"/>

    <x-bs::navbar.collapse id="shipping-address">
        <div class="d-grid flex-grow-1 gap-1 mt-3">
            <x-bs::group label="{{ __('To') }}" inline>{{ $shippingAddress->to }}</x-bs::group>
            <x-bs::group label="{{ __('Phone') }}" inline>
                <a href="tel:{{ $shippingAddress->phone}}">{{ $shippingAddress->phone }}</a>
            </x-bs::group>
            <x-bs::group label="{{ __('Street') }}" inline>{{ $shippingAddress->street }} {{ $shippingAddress->street_no }}</x-bs::group>
            <x-bs::group label="{{ __('City') }}" inline>{{ $shippingAddress->city }}</x-bs::group>
            <x-bs::group label="{{ __('Postcode') }}" inline>{{ $shippingAddress->postcode }}</x-bs::group>
            <x-bs::group label="{{ __('Region') }}" inline>{{ $shippingAddress->province ?? '' }}</x-bs::group>
            <x-bs::group label="{{ __('Country') }}" inline>{{ $country->name }}</x-bs::group>
        </div>

        @include('eshop::dashboard.cart.partials.show.shipping-address-modal')
    </x-bs::navbar.collapse>
</x-bs::navbar>
