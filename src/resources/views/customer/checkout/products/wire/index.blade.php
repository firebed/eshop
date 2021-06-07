<div>
    @if($order->products->isNotEmpty())
        <div class="d-grid gap-3">
            <h1 class="fs-3 fw-normal">{{ __('Your cart') }}</h1>

            <div class="row g-5">
                <div class="col-12 col-md">
                    <x-bs::card class="shadow-none">
                        <x-bs::card.body>
                            <div class="table-responsive bg-white">
                                @include('eshop::customer.checkout.products.wire.partials.checkout-products-table')
                            </div>
                        </x-bs::card.body>
                    </x-bs::card>
                </div>

                @include('eshop::customer.checkout.products.wire.partials.checkout-products-summary')

                @includeWhen(Auth::guest(), 'eshop::customer.checkout.partials.checkout-login-modal')
            </div>
        </div>
    @else
        <div class="d-grid gap-4 text-center">
            <div class="fs-4">{{ __('Your cart is empty') }}</div>
            <div><em class="fas fa-cart-plus fa-10x text-gray-500"></em></div>
        </div>
    @endif
</div>
