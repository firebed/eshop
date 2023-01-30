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

                    @can('Create voucher')
                        <x-bs::dropdown.item id="create-vouchers" href="#"><em class="fas fa-plus text-secondary me-2"></em> Έκδοση Voucher</x-bs::dropdown.item>
                        <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#print-vouchers-modal" id="print-vouchers" href="#"><em class="fas fa-print text-secondary me-2"></em> Εκτύπωση Voucher</x-bs::dropdown.item>
                    @endif

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

    <form action="{{ route('carts.print-vouchers') }}" method="post" target="_blank">
        @csrf

        @include('eshop::dashboard.voucher.partials.print-vouchers-modal', ['cartIds' => []])
    </form>
</div>

@push('footer_scripts')
    <script>
        document.getElementById('create-vouchers').addEventListener('click', e => {
            e.preventDefault();
            const values = [...document.querySelectorAll("input[type=checkbox][id^=cart-]:checked")].map(e => parseInt(e.value))
            const url = "{{ route('vouchers.create') }}?ids=" + encodeURIComponent(JSON.stringify(values));
            window.open(url, '_blank').focus()
        })

        document.getElementById('print-vouchers').addEventListener('click', e => {
            e.preventDefault();
            const modal = document.querySelector('#print-vouchers-modal .modal-body');
            modal.querySelectorAll("input[name='ids[]']").forEach(i => i.remove());

            [...document.querySelectorAll("input[type=checkbox][id^=cart-]:checked")]
                .map(e => parseInt(e.value))
                .forEach(cartId => {
                    const input = document.createElement("input");
                    input.name = "ids[]";
                    input.value = cartId;
                    input.type = 'hidden';

                    modal.appendChild(input);
                });
        })
    </script>
@endpush