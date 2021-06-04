<x-bs::navbar expand="xxl" class="card shadow-sm flex-xxl-wrap">
    <x-bs::navbar.brand class="d-xxl-flex justify-content-xxl-between w-xxl-100 py-0">
        <span>{{ __('Invoice') }}</span>
        <x-bs::button.link class="d-none d-xxl-block p-0" wire:click="edit">{{ __("Edit") }}</x-bs::button.link>
    </x-bs::navbar.brand>

    <x-bs::navbar.toggler target="invoice"/>

    <x-bs::navbar.collapse id="invoice">
        <div class="d-grid flex-grow-1 gap-1 mt-3">
            @if($isInvoice)
                <x-bs::group label="{{ __('Company name') }}" inline>{{ $invoice->name }}</x-bs::group>
                <x-bs::group label="{{ __('Company job') }}" inline>{{ $invoice->job }}</x-bs::group>

                <x-bs::group label="{{ __('Vat number') }}" inline>{{ $invoice->vat }}</x-bs::group>
                <x-bs::group label="{{ __('Tax office') }}" inline>{{ $invoice->tax_office }}</x-bs::group>

                <x-bs::group label="{{ __('Street') }}" inline>{{ $invoiceBilling->street }}</x-bs::group>
                <x-bs::group label="{{ __('State/Province') }}" inline>{{ $invoiceBilling->province ?? '' }}</x-bs::group>
                <x-bs::group label="{{ __('Postcode') }}" inline>{{ $invoiceBilling->postcode }}</x-bs::group>
                <x-bs::group label="{{ __('City') }}" inline>{{ $invoiceBilling->city }}</x-bs::group>
                <x-bs::group label="{{ __('Country') }}" inline>{{ $country->name ?? '' }}</x-bs::group>
            @else
                <div class="text-secondary">{{ __("Not an invoice") }}</div>
            @endif
        </div>

        @include('dashboard.cart.partials.show.cart-invoice-modal')
    </x-bs::navbar.collapse>
</x-bs::navbar>
