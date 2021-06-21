<x-bs::navbar expand="xxl" class="card shadow-sm flex-xxl-wrap">
    <x-bs::navbar.brand class="d-xxl-flex justify-content-xxl-between w-xxl-100 py-0">
        <span>{{ __('Billing Address') }}</span>
        <x-bs::button.link class="d-none d-xxl-block p-0" wire:click="$toggle('showModal')">{{ __("Edit") }}</x-bs::button.link>
    </x-bs::navbar.brand>

    <x-bs::navbar.toggler target="billing-address"/>

    <x-bs::navbar.collapse id="billing-address">
        <div class="d-grid flex-grow-1 gap-1 mt-3">
            @if($billingAddress->id)
                <x-bs::group label="{{ __('Street') }}" inline>{{ $billingAddress->street }}</x-bs::group>
                <x-bs::group label="{{ __('City') }}" inline>{{ $billingAddress->city }}</x-bs::group>
                <x-bs::group label="{{ __('Postcode') }}" inline>{{ $billingAddress->postcode }}</x-bs::group>
                <x-bs::group label="{{ __('Region') }}" inline>{{ $billingAddress->province }}</x-bs::group>
                <x-bs::group label="{{ __('Country') }}" inline>{{ $country->name ?? '' }}</x-bs::group>
            @else
                <div class="text-secondary">{{ __("Same as shipping address") }}</div>
            @endif
        </div>

        @include('eshop::dashboard.cart.partials.show.billing-address-modal')
    </x-bs::navbar.collapse>
</x-bs::navbar>
