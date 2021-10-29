<div>
    <x-bs::navbar expand="xxl" class="card shadow-sm flex-xxl-wrap">
        <x-bs::navbar.brand class="d-xxl-flex justify-content-xxl-between w-xxl-100 py-0">{{ __('Invoice') }}</x-bs::navbar.brand>

        <x-bs::navbar.toggler target="invoice"/>

        <x-bs::navbar.collapse id="invoice">
            <div class="d-grid flex-grow-1 gap-1 mt-3">
                @if($isInvoice)
                    <a href="#" class="text-decoration-none" wire:click.prevent="edit">{{ __("Edit") }}</a>

                    <x-bs::group label="{{ __('Company name') }}" inline>{{ $invoice->name }}</x-bs::group>
                    <x-bs::group label="{{ __('Company job') }}" inline>{{ $invoice->job }}</x-bs::group>

                    <x-bs::group label="{{ __('Vat number') }}" inline>{{ $invoice->vat_number }}</x-bs::group>
                    <x-bs::group label="{{ __('Tax office') }}" inline>{{ $invoice->tax_authority }}</x-bs::group>

                    <x-bs::group label="{{ __('Street') }}" inline>{{ $invoiceBilling->street }} {{ $invoiceBilling->street_no }}</x-bs::group>
                    <x-bs::group label="{{ __('State/Province') }}" inline>{{ $invoiceBilling->province ?? '' }}</x-bs::group>
                    <x-bs::group label="{{ __('Postcode') }}" inline>{{ $invoiceBilling->postcode }}</x-bs::group>
                    <x-bs::group label="{{ __('City') }}" inline>{{ $invoiceBilling->city }}</x-bs::group>
                    <x-bs::group label="{{ __('Country') }}" inline>{{ $country->name ?? '' }}</x-bs::group>
                @else
                    <div class="text-secondary">{{ __("Not an invoice") }}</div>
                @endif
            </div>
        </x-bs::navbar.collapse>
    </x-bs::navbar>

    @include('eshop::dashboard.cart.partials.show.cart-invoice-modal')
</div>