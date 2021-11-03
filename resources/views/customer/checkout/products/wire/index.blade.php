<div class="d-grid gap-3">
    @if($order->products->isNotEmpty())
        <h1 class="fs-3 fw-normal">{{ __('Your cart') }}</h1>

        @if(session()->has('insufficient-quantity'))
            <div class="alert bg-red-500 text-light p-3">
                <em class="fas fa-exclamation-circle"></em> {{ __("Some of the products are not available in requested quantity. Please check your cart.") }}
            </div>
        @elseif(session()->has('products-values-changed'))
            <div class="alert bg-red-500 text-light p-3">
                <em class="fas fa-exclamation-circle"></em> {{ __("The prices of some products have changed. Please check your cart.") }}
            </div>
        @endif

        <div class="row row-cols-1 row-cols-lg-2 gx-5 gy-4">
            <div class="col flex-grow-1">
                <x-bs::card class="shadow-none">
                    <x-bs::card.body>
                        @include('eshop::customer.checkout.products.wire.partials.checkout-products-table')
                    </x-bs::card.body>
                </x-bs::card>
            </div>

            <aside wire:loading.class="opacity-75" class="col w-lg-25r align-self-start sticky-md-top">
                @include('eshop::customer.checkout.products.wire.partials.checkout-products-summary')
            </aside>

            @includeWhen(Auth::guest(), 'eshop::customer.checkout.partials.checkout-login-modal')
        </div>
    @else
        <div class="col-12 d-grid gap-5 text-center">
            <h1 class="fs-3">{{ __('Your cart is empty') }}</h1>
            <div><em class="fas fa-cart-plus fa-10x text-gray-500"></em></div>
        </div>
    @endif
</div>
