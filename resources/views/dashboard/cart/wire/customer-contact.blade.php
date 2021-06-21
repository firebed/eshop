<x-bs::navbar expand="xxl" class="card shadow-sm flex-xxl-wrap">
    <x-bs::navbar.brand class="d-xxl-flex justify-content-xxl-between w-xxl-100 py-0">
        <span>{{ __('Customer') }}</span>
        <x-bs::button.link class="d-none d-xxl-block p-0" wire:click="edit">{{ __("Edit") }}</x-bs::button.link>
    </x-bs::navbar.brand>

    <x-bs::navbar.toggler target="customer-info"/>

    <x-bs::navbar.collapse id="customer-info">
        <div class="d-grid flex-grow-1 gap-1 mt-3">
            <x-bs::group label="{{ __('Name') }}" inline>{{ $contact->full_name }}</x-bs::group>
            <x-bs::group label="Email" inline>{{ $contact->email }}</x-bs::group>
            <x-bs::group label="Phone" inline>{{ $contact->phone }}</x-bs::group>
        </div>

        @include('eshop::dashboard.cart.partials.show.customer-contact-modal')
    </x-bs::navbar.collapse>
</x-bs::navbar>
