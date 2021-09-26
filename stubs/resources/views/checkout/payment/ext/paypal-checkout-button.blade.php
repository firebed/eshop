<div
    x-data="{ buttons: null }"
    x-init="
        paypal.Buttons({
            fundingSource: paypal.FUNDING.PAYPAL,

             onInit: function(data, actions) {
                buttons = actions
            },

            createOrder: () => {
                buttons.disable()
                Alpine.store('form').disable()
                return axios.post('{{ route('checkout.payment.store', app()->getLocale()) }}')
                    .then(res => res.data)
                    .catch(error => {
                        buttons.enable()
                        Alpine.store('form').enable()
                        $dispatch('dialog-notification', {type:'error', title: 'PayPal', content: error.response.data})
                    })
            },

            onApprove: (data, actions) => {
                buttons.disable()
                Alpine.store('form').disable()

                axios.post('{{ route('checkout.payment.store', app()->getLocale()) }}', { order_id: data.orderID })
                    .then(res => location.href = res.data)
                    .catch(error => {
                        buttons.enable()
                        Alpine.store('form').enable()
                        $dispatch('dialog-notification', {type:'error', title: 'PayPal', content: error.response.data})
                    })
            },

            onCancel: () => {
                buttons.enable()
                Alpine.store('form').enable()
            },

            onError: function (err) {
                buttons.enable()
                Alpine.store('form').enable()
                $dispatch('dialog-notification', {type:'error', title: 'PayPal', content: err})
            }
        })
        .render($refs.container)"
>
    <div x-ref="container" id="paypal-button-container"></div>
</div>
