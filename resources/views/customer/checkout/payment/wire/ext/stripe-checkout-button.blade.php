<x-bs::button.primary
    wire:key="stripe-payment-button"
    wire:click.prevent="pay"
    wire:loading.attr="disabled"

    x-data="{disabled: false}"
    x-on:stripe-loading.window="disabled = $event.detail"
    x-bind:disabled="disabled"

    id="pay-with-stripe"
>
    <em class="fas fa-lock me-2"></em>{{ __('Pay') . ' ' . format_currency($order->total) }}
</x-bs::button.primary>
