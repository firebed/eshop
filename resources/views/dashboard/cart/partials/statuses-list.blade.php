<x-bs::navbar expand="xxl" class="card shadow-sm p-0 g-0">
    <x-bs::navbar.brand class="d-xxl-none ps-4 py-3">{{ __('Statuses') }}</x-bs::navbar.brand>
    <x-bs::navbar.toggler class="me-4" target="cart-statuses-nav"/>

    <x-bs::navbar.collapse id="cart-statuses-nav">
        <x-bs::list class="rounded flex-grow-1">
            @if($vouchers > 0)
                <x-bs::list.link href="{{ route('pickups.create') }}" class="d-flex justify-content-between align-items-center bg-yellow-200">
                    <span>Λίστα παραλαβής</span>
                    <x-bs::badge type="warning">{{ format_number($vouchers) }}</x-bs::badge>
                </x-bs::list.link>
            @endif
            
            <x-bs::list.link href="{{ route('carts.index') }}" class="d-flex justify-content-between align-items-center">
                <span class="text-secondary">{{ __("All") }}</span>
                <x-bs::badge type="primary">{{ format_number($statuses->sum('carts_count')) }}</x-bs::badge>
            </x-bs::list.link>

            @foreach($statuses as $status)
                <x-bs::list.link href="{{ route('carts.index', ['status' => $status->id]) }}" class="d-flex justify-content-between align-items-center">
                    <span>{{ __("eshop::cart.status.$status->name") }}</span>
                    <x-bs::badge type="{{ $status->color }}">{{ format_number($status->carts_count) }}</x-bs::badge>
                </x-bs::list.link>
            @endforeach

            @if(eshop('show_incomplete_carts'))
                @can('Manage orders')
                    <x-bs::list.link href="{{ route('carts.index', ['incomplete' => true]) }}" class="d-flex justify-content-between align-items-center">
                        <span>Ανολοκλήρωτες</span>
                        <x-bs::badge class="bg-gray-200">{{ format_number($incomplete_carts_count) }}</x-bs::badge>
                    </x-bs::list.link>
                @endcan
            @endif

            @if(eshop('auto_payments'))
                <x-bs::list.link href="{{ route('carts.index', ['unpaid' => true]) }}" class="d-flex justify-content-between align-items-center">
                    <span>Απλήρωτες παραγγελίες</span>
                    <x-bs::badge class="bg-gray-200">{{ format_number($unpaid_carts) }}</x-bs::badge>
                </x-bs::list.link>
            @endif
        </x-bs::list>
    </x-bs::navbar.collapse>
</x-bs::navbar>
