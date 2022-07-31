<x-bs::card class="shadow-none" id="payment-methods">
    <h2 class="fs-5 fw-normal px-4 pt-4">2. {{ __('Select payment method') }}</h2>

    @if($errors->has('payment_method_error'))
        <x-bs::card.body>
        <div class="fw-bold text-danger">Παρακαλώ επιλέξτε μέθοδο πληρωμής</div>
        </x-bs::card.body>
    @endif
    
    @forelse($paymentMethods as $option)
        @php
            $isVisible = $option->shipping_method_id === null || $order->shippingMethod->id === $option->shipping_method_id;
        @endphp
        <x-bs::card.body class="p-4 {{ !$loop->last ? 'border-bottom' : '' }}" style="{{ $isVisible ? '' : 'display:none' }}">
            <x-bs::input.radio
                :value="$option->id"
                :checked="$country_payment_method_id == $option->id"
                :disabled="!$isVisible"
                name="country_payment_method_id"
                id="p-method-{{ $option->id }}"
                label-class="w-100 fw-500"
                data-shipping-method-id="{{ $option->shipping_method_id }}"
                data-payment-method-name="{{ $option->paymentMethod->name }}"
            >
                {{ __('eshop::payment.' . $option->paymentMethod->name) }}

                @if($option->fee > 0)
                    <small class="text-secondary">({{ format_currency($option->fee) }})</small>
                @endif
            </x-bs::input.radio>

            @if($option->paymentMethod->isCreditCard() || $option->paymentMethod->isCreditCardSimplify() || filled($option->description))
                <div x-init="new bootstrap.Collapse($el, {toggle: false})" @class(['ms-4', 'collapse', 'show' => old('country_payment_method_id', $country_payment_method_id) === $option->id])>
                    <div class="vstack pt-3">
                        @includeWhen($option->paymentMethod->isCreditCard(), 'eshop::customer.checkout.payment.ext.stripe-card')
                        
                        @includeWhen($option->paymentMethod->isCreditCardSimplify(), 'eshop::customer.checkout.payment.ext.simplify')

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

                const payload = {
                    'country_payment_method_id': evt.target.value,
                    'country_shipping_method_id': document.querySelector("input[name=country_shipping_method_id]:enabled:checked").value
                }
                
                axios.put('{{ route('checkout.payment.update', app()->getLocale()) }}', payload)
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
        
        function updatePaymentMethods(selectedShippingMethodId) {
            paymentMethods.querySelectorAll('[name=country_payment_method_id]').forEach(method => {
                const shippingMethodId = method.dataset.shippingMethodId

                if (shippingMethodId != null && shippingMethodId.length > 0) {
                    if (shippingMethodId === selectedShippingMethodId) {
                        method.closest('.card-body').style.display = 'block'
                        method.removeAttribute('disabled')
                    } else {
                        method.closest('.card-body').style.display = 'none'
                        method.setAttribute('disabled', 'disabled')
                        method.checked = false
                    }
                }
            })

            const checkedPaymentMethod = paymentMethods.querySelector("[name=country_payment_method_id]:checked")
            if (checkedPaymentMethod == null || checkedPaymentMethod.dataset.shippingMethodId !== selectedShippingMethodId) {
                const firstPaymentMethod = paymentMethods.querySelector("[name=country_payment_method_id]:enabled")
                if (firstPaymentMethod != null) {
                    firstPaymentMethod.checked = true
                }
            }
        }
    </script>
@endpush
