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
                return axios.post('{{ route('checkout.payment.store', app()->getLocale()) }}')
                    .then(res => res.data)
                    .catch(error => {
                        buttons.enable()
                        $dispatch('dialog-notification', {type:'error', title: 'PayPal', content: error.response.data})
                    })
            },

            onApprove: (data, actions) => {
                buttons.disable()

                axios.post('{{ route('checkout.payment.store', app()->getLocale()) }}', { order_id: data.orderID })
                    .then(res => location.href = res.data)
                    .catch(error => {
                        buttons.enable()
                        $dispatch('dialog-notification', {type:'error', title: 'PayPal', content: error.response.data})
                    })
            },

            onCancel: () => {
                buttons.enable()
            },

            onError: function (err) {
                buttons.enable()
                //$dispatch('dialog-notification', {type: 'error', title: 'PayPal Error', content: err})
            }
        })
        .render($refs.container)"
>
    <div x-ref="container" id="paypal-button-container"></div>
</div>
