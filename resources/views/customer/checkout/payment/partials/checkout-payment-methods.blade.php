<x-bs::card class="shadow-none" id="payment-methods">
    <h2 class="fs-5 fw-normal px-4 pt-4">2. {{ __('Select payment method') }}</h2>

    @forelse($paymentMethods as $option)
        <x-bs::card.body class="p-4 {{ !$loop->last ? 'border-bottom' : '' }}">
            <x-bs::input.radio
                    :value="$option->id"
                    :checked="$order->payment_method_id == $option->id"
                    name="country_payment_method_id"
                    error="country_payment_method_id"
                    id="p-method-{{ $option->id }}"
                    label-class="w-100 fw-500"
                    data-payment-method-name="{{ $option->paymentMethod->name }}"
            >
                {{ __('eshop::payment.' . $option->paymentMethod->name) }}

                @if($option->fee > 0)
                    <small class="text-secondary">({{ format_currency($option->fee) }})</small>
                @endif
            </x-bs::input.radio>

            @if($option->isCreditCard() || filled($option->description))
                <div x-init="new bootstrap.Collapse($el, {toggle: false})" @class(['ms-4', 'collapse', 'show' => old('payment_method_id', $order->payment_method_id) === $option->id])>
                    <div class="vstack pt-3">
                        @includeWhen($option->paymentMethod->isCreditCard(), 'eshop::customer.checkout.payment.ext.stripe-card')

                        @if(filled($option->description))
                            {!! $option->description !!}
                        @endif
                    </div>
                </div>
            @endif
        </x-bs::card.body>
    @empty
    @endforelse
</x-bs::card>

@push('footer_scripts')
    <script>
        const paymentMethods = document.getElementById('payment-methods')

        paymentMethods.addEventListener('change', evt => {
            if (evt.target.matches('[name=country_payment_method_id]')) {

                axios.put('{{ route('checkout.payment.update', app()->getLocale()) }}', {'country_payment_method_id': evt.target.value })
                    .then(res => document.getElementById('checkout-payment-summary').outerHTML = res.data);

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
