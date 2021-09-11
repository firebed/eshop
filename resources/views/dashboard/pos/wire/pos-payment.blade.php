<div wire:ignore.self class="offcanvas offcanvas-end px-0" tabindex="-1" id="payment-form">
    <div class="offcanvas-header border-bottom">
        <div class="fs-5 fw-500">Στοιχεία πληρωμής</div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
        <div class="row g-3">
            <div>Υπολογισμός τελών διακίνησης για: <strong>{{ $country->name ?? '' }}</strong></div>

            <x-bs::input.floating-label for="payment-method" label="{{ __('Payment method') }}">
                <x-bs::input.select wire:model.defer="method" name="payment_method_id" error="method" id="payment-method">
                    <option value="">{{ __('Select payment method') }}</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->id }}">{{ __('eshop::payment.' . $method->name) }}</option>
                    @endforeach
                </x-bs::input.select>
            </x-bs::input.floating-label>

            <x-bs::input.floating-label x-data="{fee: 0 }" for="payment-fee" label="{{ __('Payment fee') }}">
                <x-bs::input.money wire:model.lazy="fee" x-effect="fee = value" error="payment_fee" id="payment-fee" placeholder="{{ __('Payment fee') }}"/>
                <input type="hidden" x-model="fee" name="payment_fee"/>
            </x-bs::input.floating-label>

            <div class="text-center">
                <button wire:click.prevent="calculatePayment" wire:loading.attr="disabled" class="btn btn-warning">
                    <em wire:loading wire:target="calculatePayment" class="fas fa-spinner fa-spin me-2"></em>
                    Υπολογισμός τελών
                </button>
            </div>
        </div>
    </div>
</div>