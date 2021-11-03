<button x-bind:disabled="$store.form.disabled" type="submit" class="btn btn-green" id="pay-with-stripe">
    <div x-cloak x-show="$store.form.disabled" class="spinner-border spinner-border-sm" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>

    <em x-show="!$store.form.disabled" class="fas fa-lock me-2"></em>

    {{ __('Pay') . ' ' . format_currency($order->total) }}
</button>
