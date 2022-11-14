<div class="d-grid gap-3">
    @include('eshop::dashboard.cart.partials.index.carts-toolbar')

    @if($totalUnpaidByCourier->isNotEmpty())
        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3">
            @foreach($totalUnpaidByCourier as $smid => $total)
                @php($method = $shippingMethods->find($smid))
                <div class="col">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="small text-secondary text-truncate block">{{ $method ? __("eshop::shipping.$method->name") : "Χωρίς μεταφορική" }}</div>
                            <div class="fw-500">{{ format_currency($total) }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

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

    <livewire:dashboard.cart.cash-payments/>
</div>
