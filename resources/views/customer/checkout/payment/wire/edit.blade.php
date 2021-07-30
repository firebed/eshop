<form id="checkout-form">

    @if(session()->has('order-total-changed'))
        <x-bs::alert type="warning" wire:key="order-total-changed">
            {{ __("Your cart has changed during payment. Please check your products and try again.") }}
        </x-bs::alert>
    @endif

    @if($paymentMethods->isEmpty())
        <x-bs::alert type="danger" wire:key="empty-payment-methods">
            {{ __("There aren't any payment options available right now.") }}
        </x-bs::alert>
    @endif

    @if($shippingMethods->isEmpty())
        <x-bs::alert type="danger" wire:key="empty-shipping-methods">
            {{ __("There aren't any payment options available right now.") }}
        </x-bs::alert>
    @endif

    <div class="row row-cols-1 row-cols-lg-2 gx-5 gy-4">
        <div class="col flex-grow-1">
            <div class="d-grid gap-4 align-items-start">
                @include('eshop::customer.checkout.payment.wire.partials.checkout-shipping-methods')
                @include('eshop::customer.checkout.payment.wire.partials.checkout-payment-methods')

                <div class="d-none d-lg-flex">
                    <a href="{{ route('checkout.details.edit', app()->getLocale()) }}" class="btn btn-outline-secondary px-3">
                        <em class="fas fa-chevron-left me-3"></em>{{ __('Back to shipping address') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col w-lg-25r align-self-start sticky-md-top" style="top: 2rem">
            @include('eshop::customer.checkout.payment.wire.partials.checkout-payment-summary')
        </div>

        <div class="d-flex d-lg-none">
            <a href="{{ route('checkout.details.edit', app()->getLocale()) }}" class="btn btn-outline-secondary px-3">
                <em class="fas fa-chevron-left me-3"></em>{{ __('Back to shipping address') }}
            </a>
        </div>
    </div>
</form>
