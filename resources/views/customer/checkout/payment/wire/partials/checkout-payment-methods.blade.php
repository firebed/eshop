<x-bs::card class="shadow-none" id="payment-methods">
    <h2 class="fs-5 fw-normal px-4 pt-4">2. {{ __('Select payment method') }}</h2>

    @forelse($paymentMethods as $option)
        <x-bs::card.body wire:key="payment-option-{{ $option->id }}" class="p-4 border-bottom d-flex flex-column">
            <x-bs::input.radio wire:model="payment_method_id" wire:loading.attr="disabled" wire:target="shipping_method_id, pay, payWithStripe, chargeStripeCard, confirmStripePayment, payWithPayPal, confirmPayPalPayment" name="payment_method_id" error="payment_method_id" id="p-method-{{ $option->id }}" :value="$option->payment_method_id" label-class="w-100">
            <span class="d-grid">
                <span class="fw-500">{{ __($option->paymentMethod->name) }} @if($option->fee > 0)<small class="text-secondary">({{ format_currency($option->fee) }})</small>@endif</span>
            </span>
            </x-bs::input.radio>
            @if($option->payment_method_id == 2 || filled($option->description))
                <span class="collapse {{ $option->payment_method_id == $order->payment_method_id ? 'show' : '' }}">
                    @includeWhen($option->payment_method_id === 2, 'customer.checkout.payment.wire.ext.stripe-card')

                    @if(filled($option->description))
                        {!! $option->description !!}
                    @endif
                </span>
            @endif
        </x-bs::card.body>
    @empty
    @endforelse
</x-bs::card>

@push('footer_scripts')
    <script>
        const paymentMethods = document.getElementById('payment-methods')
        const collapseElementList = [].slice.call(paymentMethods.querySelectorAll('.collapse'))
        collapseElementList.map(el => new bootstrap.Collapse(el, {toggle: false}))

        paymentMethods.addEventListener('change', evt => {
            if (evt.target.matches('[name=payment_method_id]')) {
                const prev = paymentMethods.querySelector('.collapse.show')
                if (prev) {
                    bootstrap.Collapse.getInstance(prev).hide()
                }

                const collapse = evt.target.parentElement.parentElement.querySelector('.collapse');
                if (collapse) {
                    bootstrap.Collapse.getInstance(collapse).show();
                }
            }
        })
    </script>
@endpush
