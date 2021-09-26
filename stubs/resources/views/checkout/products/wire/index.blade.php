<div class="row row-cols-1 row-cols-lg-2 gx-5 gy-4">
    @if($order->products->isNotEmpty())

        <div class="col flex-grow-1">
            <x-bs::card class="shadow-none">
                <x-bs::card.body>
                    @include('checkout.products.wire.partials.checkout-products-table')
                </x-bs::card.body>
            </x-bs::card>
        </div>

        <aside wire:loading.class="opacity-75" class="col w-lg-25r align-self-start sticky-md-top position-relative">
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
