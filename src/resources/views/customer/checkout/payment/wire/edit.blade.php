<form id="checkout-form">

    @if(session()->has('order-total-changed'))
        <x-bs::alert type="warning">
            {{ __("Your cart has changed during payment. Please check your products and try again.") }}
        </x-bs::alert>
    @endif

    <div class="row gy-4 gx-5">
        <div class="col-12 col-lg">
            <div class="d-grid gap-4 align-items-start">
                @include('customer.checkout.payment.wire.partials.checkout-shipping-methods')
                @include('customer.checkout.payment.wire.partials.checkout-payment-methods')

                <div class="d-none d-lg-flex">
                    <a href="{{ route('checkout.details.edit', app()->getLocale()) }}" class="btn btn-outline-secondary px-3">
                        <em class="fas fa-chevron-left me-3"></em>{{ __('Back to shipping address') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-auto w-lg-25r align-self-start sticky-md-top" style="top: 2rem">
            @include('customer.checkout.payment.wire.partials.checkout-payment-summary')
        </div>

        <div class="d-flex d-lg-none">
            <a href="{{ route('checkout.details.edit', app()->getLocale()) }}" class="btn btn-outline-secondary px-3">
                <em class="fas fa-chevron-left me-3"></em>{{ __('Back to shipping address') }}
            </a>
        </div>
    </div>
</form>
