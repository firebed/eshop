<div class="d-grid gap-3">
    <h1 class="fs-3 mb-0">{{ __("Orders") }}</h1>

    @include('eshop::dashboard.cart.partials.index.cart-search')

    <div class="card shadow-sm">
        <div class="table-responsive">
            @include('eshop::dashboard.cart.partials.index.carts-table')
        </div>
    </div>

    <form wire:submit.prevent="delete">
        @include('eshop::dashboard.cart.partials.index.carts-delete-modal')
    </form>
</div>