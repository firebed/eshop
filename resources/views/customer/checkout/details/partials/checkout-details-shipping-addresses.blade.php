<x-bs::card class="shadow-none" id="shipping-addresses">
    @isset($addresses)
        @foreach($addresses as $address)
            <x-bs::card.body class="p-4 border-bottom d-flex flex-column">
                <x-bs::input.radio
                    value="{{ $address->id }}"
                    :checked="old('selected_shipping_id', $selected_shipping_id) == $address->id"
                    name="selected_shipping_id"
                    error="selected_shipping_id"
                    id="address-{{ $address->id }}"
                    label-class="w-100"
                >
                <span class="d-grid">
                    <span class="fw-500">{{ $address->street }} {{ $address->street_no }}, {{ $address->city }} {{ $address->postcode }}</span>
                    <span class="small text-secondary">{{ $address->to }}</span>
                </span>
                </x-bs::input.radio>

                <div x-init="new bootstrap.Collapse($el, {toggle: false})" @class(['ms-4', 'collapse', 'show' => $address->id === old('selected_shipping_id', $selected_shipping_id)])>
                    <div class="vstack small text-secondary">
                        <div>{{ $address->phone }}</div>
                        <div>{{ auth()->user()?->email }}</div>
                    </div>
                </div>
            </x-bs::card.body>
        @endforeach
    @endisset

    <x-bs::card.body class="p-4">
        <x-bs::input.radio
            value=""
            :checked="old('selected_shipping_id', $selected_shipping_id) == null"
            name="selected_shipping_id"
            error="selected_shipping_id"
            id="new-address"
            label-class="w-100"
        >{{ __('New address') }}</x-bs::input.radio>

        <div x-init="new bootstrap.Collapse($el, {toggle: false})" class="collapse @if(old('selected_shipping_id', $selected_shipping_id) === null) show @endif">
            <div class="row row-cols-2 g-3 mt-0">
                <div class="col-12 col-sm-6">
                    <x-bs::input.floating-label for="first-name" label="{{ __('Name') }}">
                        <x-bs::input.text value="{{ old('shippingAddress.first_name', $shipping?->first_name) ?? '' }}" name="shippingAddress[first_name]" error="shippingAddress.first_name" id="first-name" placeholder="{{ __('Name') }}"/>
                    </x-bs::input.floating-label>
                </div>

                <div class="col-12 col-sm-6">
                    <x-bs::input.floating-label for="last-name" label="{{ __('Surname') }}">
                        <x-bs::input.text value="{{ old('shippingAddress.last_name', $shipping?->last_name) ?? '' }}" name="shippingAddress[last_name]" error="shippingAddress.last_name" id="last-name" placeholder="{{ __('Surname') }}"/>
                    </x-bs::input.floating-label>
                </div>

                @guest
                    <div class="col-12 col-sm-6">
                        <x-bs::input.floating-label for="email" label="{{ __('Email') }}">
                            <x-bs::input.email value="{{ old('email', $order->email) ?? '' }}" name="email" error="email" id="email" placeholder="{{ __('Email') }}"/>
                        </x-bs::input.floating-label>
                    </div>
                @endguest

                <div class="@guest col-12 col-sm-6 @else col-12 @endguest">
                    <x-bs::input.floating-label for="phone" label="{{ __('Phone') }}">
                        <x-bs::input.text value="{{ old('shippingAddress.phone', $shipping?->phone) ?? '' }}" name="shippingAddress[phone]" error="shippingAddress.phone" id="phone" placeholder="{{ __('Phone') }}"/>
                    </x-bs::input.floating-label>
                </div>

                <div class="col-12 col-sm-6">
                    <x-bs::input.floating-label for="country" label="{{ __('Country') }}">
                        <x-bs::input.select name="shippingAddress[country_id]" error="shippingAddress.country_id" id="shipping-country" class="pb-2">
                            <option value="">{{ __('Select country') }}</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" @if(old('shippingAddress.country_id', $shipping->country_id ?? $userCountry?->id) == $country->id) selected @endif>{{ $country->name }}</option>
                            @endforeach
                        </x-bs::input.select>
                    </x-bs::input.floating-label>
                </div>

                <div id="provinces-container" class="col-12 col-sm-6">
                    @include('eshop::customer.checkout.details.partials.provinces')
                </div>

                <div class="col-12 col-sm-8">
                    <x-bs::input.floating-label for="street" label="{{ __('Street') }}">
                        <x-bs::input.text value="{{ old('shippingAddress.street', $shipping?->street) ?? '' }}" name="shippingAddress[street]" error="shippingAddress.street" id="street" placeholder="{{ __('Street') }}"/>
                    </x-bs::input.floating-label>
                </div>

                <div class="col-12 col-sm-4">
                    <x-bs::input.floating-label for="street-no" label="{{ __('Street no') }}">
                        <x-bs::input.text value="{{ old('shippingAddress.street_no', $shipping?->street_no) ?? '' }}" name="shippingAddress[street_no]" error="shippingAddress.street_no" id="street-no" placeholder="{{ __('Street no') }}"/>
                    </x-bs::input.floating-label>
                </div>

                <div class="col-12 col-sm-8">
                    <x-bs::input.floating-label for="city" label="{{ __('City') }}">
                        <x-bs::input.text value="{{ old('shippingAddress.city', $shipping?->city) ?? '' }}" name="shippingAddress[city]" error="shippingAddress.city" id="city" placeholder="{{ __('City') }}"/>
                    </x-bs::input.floating-label>
                </div>

                <div class="col-12 col-sm-4">
                    <x-bs::input.floating-label for="shipping-postcode" label="{{ __('Postcode') }}">
                        <x-bs::input.text value="{{ old('shippingAddress.postcode', $shipping?->postcode) ?? '' }}" name="shippingAddress[postcode]" error="shippingAddress.postcode" id="shipping-postcode" placeholder="{{ __('Postcode') }}"/>
                    </x-bs::input.floating-label>
                </div>
            </div>
        </div>
    </x-bs::card.body>
</x-bs::card>

@push('footer_scripts')
    <script>
        const container = document.getElementById('shipping-addresses')
        let prev = container.querySelector('.collapse.show')

        container.addEventListener('change', evt => {
            if (evt.target.matches('[name=selected_shipping_id]')) {

                const summary = document.getElementById('checkout-details-summary')
                const submit = summary.querySelector('button[type=submit]')

                submit.setAttribute('disabled', 'disabled')

                axios.post('{{ route('checkout.details.userShipping', app()->getLocale()) }}', {selected_shipping_id: evt.target.value})
                    .then(res => summary.outerHTML = res.data)
                    .catch(() => submit.removeAttribute('disabled'))

                if (prev) {
                    // prev.querySelectorAll('input, select').forEach(i => i.setAttribute('disabled', 'disabled'));
                    bootstrap.Collapse.getInstance(prev).hide()
                }

                const collapse = evt.target.parentElement.parentElement.querySelector('.collapse');
                if (collapse) {
                    prev = collapse
                    // collapse.querySelectorAll('input, select').forEach(i => i.removeAttribute('disabled'));
                    bootstrap.Collapse.getInstance(collapse).show();
                }
            }
        })

        document.getElementById('shipping-country').addEventListener('change', evt => {
            const summary = document.getElementById('checkout-details-summary')
            const submit = summary.querySelector('button[type=submit]')

            submit.setAttribute('disabled', 'disabled')

            axios.post('{{ route('checkout.details.shippingCountry', app()->getLocale()) }}', {
                'country_id': evt.target.value,
                'postcode': document.getElementById('shipping-postcode').value
            })
                .then(res => {
                    document.getElementById('provinces-container').innerHTML = res.data.provinces
                    summary.outerHTML = res.data.summary
                })
                .catch(() => submit.removeAttribute('disabled'));
        })
    </script>
@endpush
