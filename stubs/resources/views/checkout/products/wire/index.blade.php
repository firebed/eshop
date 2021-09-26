<div class="row gx-5 gy-4">
    @if($order->products->isNotEmpty())
        <h1 class="col-12 fs-3 fw-normal mb-0">{{ __('Your cart') }}</h1>

        <div class="col-12 col-md-7 col-lg-8">
            <x-bs::card class="shadow-none">
                <x-bs::card.body>
                    <div class="table-responsive bg-white">
                        @include('checkout.products.wire.partials.checkout-products-table')
                    </div>
                </x-bs::card.body>
            </x-bs::card>
        </div>

        <aside wire:loading.class="opacity-75" class="col align-self-start sticky-md-top position-relative">
            <div wire:loading class="position-absolute start-50 top-50 translate-middle">
                <em class="fa fa-fan fa-spin text-primary fs-5"></em>
            </div>

            <div wire:loading class="position-absolute start-50 top-50 translate-middle">
                <em class="fa fa-fan fa-spin text-primary fs-5"></em>
            </div>

            @include('checkout.products.wire.partials.checkout-products-summary')
        </aside>

        @includeWhen(Auth::guest(), 'checkout.partials.checkout-login-modal')
    @else
        <div class="d-grid gap-4 text-center">
            <div class="fs-4">{{ __('Your cart is empty') }}</div>
            <div><em class="fas fa-cart-plus fa-10x text-gray-500"></em></div>
        </div>
    @endif
</div>
