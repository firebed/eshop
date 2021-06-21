<x-bs::navbar expand="xxl" class="card shadow-sm p-0 g-0">
    <x-bs::navbar.brand class="d-xxl-none ps-4 py-3">{{ __('Statuses') }}</x-bs::navbar.brand>
    <x-bs::navbar.toggler class="me-4" target="cart-statuses-nav"/>

    <x-bs::navbar.collapse id="cart-statuses-nav">
        <x-bs::list class="rounded flex-grow-1">
            <x-bs::list.link href="{{ route('carts.index') }}" class="d-flex justify-content-between align-items-center">
                <span class="text-secondary">{{ __("All") }}</span>
                <x-bs::badge type="primary">{{ format_number($statuses->sum('carts_count')) }}</x-bs::badge>
            </x-bs::list.link>

            @foreach($statuses as $status)
                <x-bs::list.link href="{{ route('carts.index', ['status' => $status->id]) }}" class="d-flex justify-content-between align-items-center">
                    <span>{{ $status->name }}</span>
                    <x-bs::badge type="{{ $status->color }}">{{ format_number($status->carts_count) }}</x-bs::badge>
                </x-bs::list.link>
            @endforeach
        </x-bs::list>
    </x-bs::navbar.collapse>
</x-bs::navbar>
