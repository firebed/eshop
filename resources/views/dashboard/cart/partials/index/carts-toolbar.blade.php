<div class="row g-3">
    <div class="col-12 col-xl-4">
        <x-bs::input.search wire:model="filter" placeholder="{{ __('Filter orders') }}"/>
    </div>

    <div class="col-12 col-sm-6 col-xl-4 d-flex gap-3">
        <x-bs::input.select wire:model="shipping_method_id">
            <option value="">{{ __("Shipping") }}</option>
            @foreach($shippingMethods as $method)
                <option value="{{ $method->id }}">{{ __($method->name) }}</option>
            @endforeach
        </x-bs::input.select>

        <x-bs::input.select wire:model="payment_method_id">
            <option value="">{{ __("Payment") }}</option>
            @foreach($paymentMethods as $method)
                <option value="{{ $method->id }}">{{ __("eshop::payment.$method->name") }}</option>
            @endforeach
        </x-bs::input.select>
    </div>

    <div class="col-12 col-sm-6 col-xl-4">
        <div class="d-flex gap-3">
            <x-bs::input.select wire:model="per_page">
                @for($i = 20; $i <= 100; $i += 20)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </x-bs::input.select>

            <x-bs::button.white wire:click="exportSelected" wire:loading.attr="disabled" wire:target="exportSelected" class="text-nowrap">
                <em class="fa fa-file-excel text-green-500 me-2"></em> {{ __("Excel") }}
            </x-bs::button.white>

            <x-bs::dropdown>
                <x-bs::dropdown.button id="bulk-actions" class="btn-white shadow-sm">{{ __("Actions") }}</x-bs::dropdown.button>
                <x-bs::dropdown.menu button="bulk-actions">
                    <x-bs::dropdown.item wire:click.prevent="editStatuses"><em class="fas fa-tasks me-2 text-secondary"></em>{{ __("Change status") }}</x-bs::dropdown.item>
                    @if(eshop('auto_payments'))
                        <x-bs::dropdown.item wire:click.prevent="markAsPaid"><em class="fas fa-hand-holding-usd me-2 text-secondary"></em>{{ __("Mark as paid") }}</x-bs::dropdown.item>

                        @can('Cash payments')
                            <x-bs::dropdown.item wire:click.prevent="$emit('showCashPaymentsModal')">
                                <em class="fas fa-cash-register me-2 text-secondary"></em>{{ __("Cash payments") }}
                            </x-bs::dropdown.item>
                        @endcan
                    @endif

                    <x-bs::dropdown.item wire:click.prevent="print"><em class="fas fa-print me-2 text-secondary"></em>{{ __("Print") }}</x-bs::dropdown.item>

                    @can("Manage orders")
                        <x-bs::dropdown.item wire:click.prevent="showOperators"><em class="fas fa-users me-2 text-secondary"></em>{{ __("Change operators") }}</x-bs::dropdown.item>

                        @can('Delete orders')
                            <x-bs::dropdown.divider/>
                            <x-bs::dropdown.item wire:click.prevent="confirmDelete()"><em class="far fa-trash-alt me-2 text-secondary"></em>{{ __("Delete") }}</x-bs::dropdown.item>
                        @endcan
                    @endcan
                </x-bs::dropdown.menu>
            </x-bs::dropdown>
        </div>
    </div>
</div>
