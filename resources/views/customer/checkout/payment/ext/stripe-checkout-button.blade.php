<button type="submit" class="btn btn-green" id="pay-with-stripe">
    <em class="fas fa-lock me-2"></em>{{ __('Pay') . ' ' . format_currency($order->total) }}
</button>
