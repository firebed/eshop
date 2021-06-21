<x-bs::card class="shadow-none" id="shipping-methods">
    <h2 class="fs-5 fw-normal px-4 pt-4">1. {{ __('Select shipping method') }}</h2>

    @forelse($shippingMethods as $option)
        <x-bs::card.body wire:key="shipping-option-{{ $option->id }}" class="p-4 border-bottom d-flex flex-column">
            <x-bs::input.radio wire:model="shipping_method_id" wire:loading.attr="disabled" wire:target="shipping_method_id, pay, payWithStripe, chargeStripeCard, confirmStripePayment, payWithPayPal, confirmPayPalPayment" name="shipping_method_id" error="shipping_method_id" id="method-{{ $option->id }}" :value="$option->shipping_method_id" label-class="w-100">
            <span class="d-grid">
                <span class="fw-500">{{ __($option->shippingMethod->name) }} @if($option->fee > 0)<small class="text-secondary">({{ format_currency($option->fee) }})</small>@endif</span>
                @if($option->description)
                    <span class="collapse {{ old('shipping_method_id', $order->shipping_method_id) === $option->shipping_method_id ? 'show' : '' }}">
                    <d class="d-grid pt-3">
                    {!! $option->description !!}
                    </d>
                </span>
                @endif
            </span>
            </x-bs::input.radio>
        </x-bs::card.body>
    @empty
    @endforelse
</x-bs::card>

@push('footer_scripts')
    <script>
        const shippingMethods = document.getElementById('shipping-methods')
        const collapseElementList2 = [].slice.call(shippingMethods.querySelectorAll('.collapse'))
        collapseElementList2.map(el => new bootstrap.Collapse(el, {toggle: false}))

        shippingMethods.addEventListener('change', evt => {
            if (evt.target.matches('[name=shipping_method_id]')) {
                const prev = shippingMethods.querySelector('.collapse.show')
                if (prev) {
                    // prev.querySelectorAll('input, select').forEach(i => i.setAttribute('disabled', 'disabled'));
                    bootstrap.Collapse.getInstance(prev).hide()
                }

                const collapse = evt.target.parentElement.parentElement.querySelector('.collapse');
                if (collapse) {
                    // collapse.querySelectorAll('input, select').forEach(i => i.removeAttribute('disabled'));
                    bootstrap.Collapse.getInstance(collapse).show();
                }
            }
        })
    </script>
@endpush
