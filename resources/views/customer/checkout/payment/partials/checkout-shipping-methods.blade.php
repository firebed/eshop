<x-bs::card class="shadow-none" id="shipping-methods">
    <h2 class="fs-5 fw-normal px-4 pt-4">1. {{ __('Select shipping method') }}</h2>

    @if($errors->has('shipping_method_error'))
        <x-bs::card.body>
            <div class="fw-bold text-danger">{{ __("Please select a shipping method") }}</div>
        </x-bs::card.body>
    @endif
    
    @forelse($shippingMethods as $option)
        <x-bs::card.body class="p-4 {{ !$loop->last ? 'border-bottom' : '' }}">
            <x-bs::input.radio
                :value="$option->id"
                :checked="$country_shipping_method_id == $option->id"
                name="country_shipping_method_id"
                id="method-{{ $option->id }}"
                label-class="w-100 fw-500"
                data-shipping-method-id="{{ $option->shipping_method_id }}"
            >
                {{ __("eshop::shipping." . $option->shippingMethod->name) }}

                @if ($option->area?->type === 'ΔΠ' && $option->inaccessible_area_fee > 0)
                    (Δυσπρόσιτη περιοχή)
                @endif

                @if($option->total_fee > 0)
                    <small class="text-secondary">({{ format_currency($option->total_fee) }})</small>
                @elseif($option->shippingMethod->is_courier && $option->total_fee === .0)
                    <small class="text-secondary">({{ __("Free shipping") }})</small>
                @endif
            </x-bs::input.radio>

            @if($option->description || ($option->area?->shipping_method_id === $option->shipping_method_id && $option->area?->type !== null))
                <div x-init="new bootstrap.Collapse($el, {toggle: false})" @class(['ms-4', 'collapse', 'show' => old('shipping_method_id', $country_shipping_method_id) === $option->id])>
                    <div class="vstack gap-3 pt-3 text-secondary">
                        @if($option->area?->type === 'ΔΠ' && $option->inaccessible_area_fee === .0)
                            <div class="vstack">
                                <div>{{ __("Your address belongs to a hard-to-reach area and you will receive your package at your local carrier.") }}</div>
                                @if($option->area)
                                    @if($option->area->courier_address)
                                        <div>{{ __("Address") }}: {{ $option->area->courier_address }}</div>
                                    @endif

                                    @if($option->area->courier_phone)
                                        <div>{{ __("Phone") }}: {{ $option->area->courier_phone }}</div>
                                    @endif
                                @endif
                            </div>
                        @elseif($option->area?->type === 'ΔΠ')
                            <div>Η διεύθυνση σας ανήκει σε δυσπρόσιτη περιοχή και χρεώνεστε {{ format_currency($option->inaccessible_area_fee) }} επιπλέον για την αποστολή του δέματος στην περιοχή σας.</div>
                        @endif

                        {!! $option->description !!}
                    </div>
                </div>
            @endif
        </x-bs::card.body>
    @empty
    @endforelse
</x-bs::card>

@push('footer_scripts')
    <script>
        const shippingMethods = document.getElementById('shipping-methods')
        shippingMethods.addEventListener('change', evt => {
            if (evt.target.matches('[name=country_shipping_method_id]')) {
                
                updatePaymentMethods(evt.target.dataset.shippingMethodId)
                
                const payload = {
                    country_shipping_method_id: evt.target.value,
                    country_payment_method_id: document.querySelector("input[name=country_payment_method_id]:enabled:checked").value
                }

                axios.put('/el/checkout/payment', payload)
                    .then(res => document.getElementById('checkout-payment-summary').outerHTML = res.data);

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
