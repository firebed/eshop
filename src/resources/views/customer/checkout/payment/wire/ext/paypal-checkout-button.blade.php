<div
    wire:ignore
    x-data="{buttons: null}"
    x-init="
        paypal.Buttons({
            fundingSource: paypal.FUNDING.PAYPAL,

             onInit: function(data, actions) {
             buttons = actions
                  // Disable the buttons
                  // actions.disable()
            },

            createOrder: () => $wire.pay(),

            onApprove: (data, actions) => {
                buttons.disable()
                $wire.confirmPayPalPayment(data.orderID).then(failed => {
                    buttons.enable()
                })
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
