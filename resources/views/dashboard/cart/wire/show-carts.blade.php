<div class="d-grid gap-3">
    @include('eshop::dashboard.cart.partials.index.carts-toolbar')

    <div class="card shadow-sm">
        <div class="table-responsive" style="overflow-y: visible">
            @include('eshop::dashboard.cart.partials.index.carts-table')
        </div>
    </div>

    <form wire:submit.prevent="delete">
        @include('eshop::dashboard.cart.partials.index.carts-delete-modal')
    </form>

    <form wire:submit.prevent="saveOperators">
        @include('eshop::dashboard.cart.partials.index.cart-operators-modal')
    </form>
    
    <form wire:submit.prevent="saveStatuses">
        @include('eshop::dashboard.cart.partials.index.cart-statuses-modal')
    </form>

    <form wire:submit.prevent="confirmMarkAsPaid">
        @include('eshop::dashboard.cart.partials.index.cart-payment-modal')
    </form>
</div>
