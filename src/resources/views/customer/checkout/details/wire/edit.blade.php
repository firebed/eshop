<form wire:submit.prevent="save">
    <div class="d-grid gap-4">
        <div class="row gx-5 gy-4">
            <div class="col-12 col-lg">
                <div class="d-grid gap-4 align-items-start">
                    @include('customer.checkout.details.wire.partials.checkout-details-shipping-addresses')
                    @include('customer.checkout.details.wire.partials.checkout-details-invoicing')

                    <x-bs::input.floating-label for="customer-notes" label="{{ __('Instructions about your order') }}">
                        <x-bs::input.textarea wire:model.defer="details" error="details" id="customer-notes" style="height: 6rem" placeholder="{{ __('Instructions about your order') }}" />
                    </x-bs::input.floating-label>

                    <div class="d-none d-lg-flex">
                        <a href="{{ route('checkout.products.index', app()->getLocale()) }}" class="btn btn-outline-secondary px-3">
                            <em class="fas fa-chevron-left me-3"></em>{{ __('Back to cart') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-auto w-lg-25r align-self-start sticky-md-top" style="top: 2rem">
                @include('customer.checkout.details.wire.partials.checkout-details-summary')
            </div>
        </div>

        <div class="d-flex d-lg-none">
            <a href="{{ route('checkout.products.index', app()->getLocale()) }}" class="btn btn-outline-secondary px-3">
                <em class="fas fa-chevron-left me-3"></em>{{ __('Back to cart') }}
            </a>
        </div>
    </div>
</form>
