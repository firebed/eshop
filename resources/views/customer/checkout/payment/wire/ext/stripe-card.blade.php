<div wire:key="stripe-payment"
     wire:ignore
     x-data="{
        stripe: Stripe('{{ config('cashier.key') }}', {locale: '{{ app()->getLocale() }}'}),
        elements: null,
        cartStyle: {
            hidePostalCode: true,
            style: {
                base: {
                    lineHeight: '40px',
                    fontSize: '15px',
                },
            }
        },
        billing_details: {
             name:  '{{ $order->shippingAddress->full_name }}',
             phone: '{{ $order->shippingAddress->phone }}',
             email: '{{ $order->email }}',
             address: {
                 city:        '{{ $order->shippingAddress->city }}',
                 country:     '{{ $order->shippingAddress->country->code }}',
                 line1:       '{{ $order->shippingAddress->full_street }}',
                 postal_code: '{{ $order->shippingAddress->postcode }}',
                 state:       '{{ $order->shippingAddress->province }}'
             }
        },
        error: null
     }"
     x-init="
        elements = stripe.elements()
        cardElement = elements.create('card', cartStyle)
        cardElement.mount($refs.card)
        cardElement.on('change', event => error = event.error ? event.error.message : null)
     "
     x-on:stripe-charge-card.window="
        $dispatch('stripe-loading', true)
        error = null
        document.getElementById('pay-with-stripe').setAttribute('disabled', 'disabled')
        stripe.createPaymentMethod('card', cardElement, {billing_details})
        .then(res => {
            if (!res.error) {
                return $wire.chargeStripeCard(res.paymentMethod.id)
            }

            error = res.error.message
            $dispatch('stripe-loading', false)
        })
        .catch(e => {
            error = e.message
            $dispatch('stripe-loading', false)
        })
     "
     x-on:stripe-payment-action-required.window="
        $dispatch('stripe-loading', true)
        const clientSecret = $event.detail
        stripe.handleCardAction(clientSecret).then(res => {
            if (!res.error) {
                return $wire.confirmStripePayment()
            }

            error = res.error.message
            $dispatch('stripe-loading', false)
        });
     "
     x-on:stripe-checkout-error.window="
        error = $event.detail
        $dispatch('stripe-loading', false)
     "
     class="d-grid mt-3 gap-2"
>
    <div x-ref="card" class="form-control bg-white"></div>
    <div wire:loading.remove x-show="error" x-text="error" class="fw-500 text-danger"></div>
</div>
