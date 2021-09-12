<div class="vstack mt-3 gap-4">
    <div class="vstack gap-2 align-items-baseline">
        <div class="hstack"><em class="fas fa-lock text-green fs-4 me-2"></em><span>Ασφαλείς online αγορές</span></div>
    </div>

    <div id="card-element" class="form-control bg-white"></div>

    <div id="card-error" class="fw-500 text-danger" style="display: none"></div>

    <div>
        <img class="img-fluid" src="{{ asset('storage/images/credit-cards.png') }}" alt="MasterCard, Visa, Amex, Discover" width="250" height="40">
    </div>
</div>

@push('footer_scripts')
    <script>
        const stripe = Stripe('{{ config('cashier.key') }}', {locale: '{{ app()->getLocale() }}'});

        const elements = stripe.elements();

        const cardElement = elements.create('card', {
            hidePostalCode: true,
            style: {
                base: {
                    lineHeight: '40px',
                    fontSize: '15px',
                },
            }
        });
        cardElement.mount('#card-element');

        const form = document.getElementById('checkout-form');

        function disableCheckoutForm() {
            document.getElementById('pay-with-stripe').setAttribute('disabled', 'disabled');
        }

        function enableCheckoutForm() {
            document.getElementById('pay-with-stripe').removeAttribute('disabled');
        }

        function error(msg) {
            document.getElementById('card-error').textContent = msg
        }

        form.addEventListener('submit', function (event) {
            const payment = document.querySelector('input[name=country_payment_method_id]:checked')
            if (payment.getAttribute('data-payment-method-name') !== 'credit_card')
                return

            event.preventDefault();
            disableCheckoutForm()
            error("")

            stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: {
                    name: '{{ $order->shippingAddress->full_name }}',
                    phone: '{{ $order->shippingAddress->phone }}',
                    email: '{{ $order->email }}',
                    address: {
                        city: '{{ $order->shippingAddress->city }}',
                        country: '{{ $order->shippingAddress->country->code }}',
                        line1: '{{ $order->shippingAddress->full_street }}',
                        postal_code: '{{ $order->shippingAddress->postcode }}',
                        state: '{{ $order->shippingAddress->province }}'
                    }
                },
            }).then(stripePaymentMethodHandler);
        });

        function stripePaymentMethodHandler(result) {
            if (result.error) {
                enableCheckoutForm()
                error(result.error.message)
            } else {
                axios.post(form.action, {payment_method_id: result.paymentMethod.id})
                    .then(handleServerResponse)
                    .catch(result => {
                        enableCheckoutForm()
                        error(result.response.data)
                    })
            }
        }

        function handleServerResponse(response) {
            if (response.data.error) {
                enableCheckoutForm()
                error(result.error.message)
            } else if (response.data.requires_action) {
                stripe.handleCardAction(response.data.client_secret).then(handleStripeResult);
            } else {
                location.href = response.data
            }
        }

        function handleStripeResult(result) {
            if (result.error) {
                enableCheckoutForm()
                error(result.error.message)
            } else {
                // The card action has been handled
                // The PaymentIntent can be confirmed again on the server
                axios.post(form.action, {payment_intent_id: result.paymentIntent.id})
                    .then(handleServerResponse)
                    .catch(result => {
                        enableCheckoutForm()
                        error(result.response.data)
                    });
            }
        }
    </script>
@endpush