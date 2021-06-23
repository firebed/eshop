<x-bs::card class="shadow-none" id="shipping-addresses">
    @isset($addresses)
        @foreach($addresses as $address)
            <x-bs::card.body class="p-4 border-bottom d-flex flex-column" wire:key="shipping-addresses-{{ $address->id }}">
                <x-bs::input.radio wire:model="selectedShipping" error="selectedShipping" id="address-{{ $address->id }}" name="selectedShipping" value="{{ $address->id }}" label-class="w-100">
                <span class="d-grid">
                    <span class="fw-500">{{ $address->street }} {{ $address->street_no }}, {{ $address->city }} {{ $address->postcode }}</span>
                    <span class="small text-secondary">{{ $address->to }}</span>
                    <span class="collapse {{ $address->id === $selectedShipping ? 'show' : '' }}">
                        <span class="d-grid">
                            <span class="small text-secondary">{{ $address->phone }}</span>
                            <span class="small text-secondary">{{ user()->email }}</span>
                        </span>
                    </span>
                </span>
                </x-bs::input.radio>
            </x-bs::card.body>
        @endforeach
    @endisset

    <x-bs::card.body class="p-4" wire:key="new-shipping-addresses">
        <x-bs::input.radio wire:model="selectedShipping" name="selectedShipping" id="new-address" value="0" label-class="w-100">{{ __('New address') }}</x-bs::input.radio>

        <div class="collapse row row-cols-2 g-3 mt-0 @if($selectedShipping === 0) show @endif">
            <div class="col-6">
                <x-bs::input.floating-label for="first-name" label="{{ __('Name') }}">
                    <x-bs::input.text wire:model.defer="shipping.first_name" error="shipping.first_name" id="first-name" placeholder="{{ __('Name') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-6">
                <x-bs::input.floating-label for="last-name" label="{{ __('Surname') }}">
                    <x-bs::input.text wire:model.defer="shipping.last_name" error="shipping.last_name" id="last-name" placeholder="{{ __('Surname') }}"/>
                </x-bs::input.floating-label>
            </div>

            @guest
                <div class="col-6">
                    <x-bs::input.floating-label for="email" label="{{ __('Email') }}">
                        <x-bs::input.email wire:model.defer="email" error="email" id="email" placeholder="{{ __('Email') }}"/>
                    </x-bs::input.floating-label>
                </div>
            @endguest

            <div class="@guest col-6 @else col-12 @endguest">
                <x-bs::input.floating-label for="phone" label="{{ __('Phone') }}">
                    <x-bs::input.text wire:model.defer="shipping.phone" error="shipping.phone" id="phone" placeholder="{{ __('Phone') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-6">
                <x-bs::input.floating-label for="country" label="{{ __('Country') }}">
                    <x-bs::input.select wire:model.debounce="shipping.country_id" error="shipping.country_id" id="shipping-country" class="pb-2">
                        <option value="">{{ __('Select country') }}</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </x-bs::input.select>
                </x-bs::input.floating-label>
            </div>

            <div class="col-6">
                <x-bs::input.floating-label for="province" label="{{ __('Province / Department') }}">
                    <x-bs::input.text wire:model.defer="shipping.province" error="shipping.province" id="province" placeholder="{{ __('Province / Department') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-8">
                <x-bs::input.floating-label for="street" label="{{ __('Street') }}">
                    <x-bs::input.text wire:model.defer="shipping.street" error="shipping.street" id="street" placeholder="{{ __('Street') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-4">
                <x-bs::input.floating-label for="street-no" label="{{ __('Street no') }}">
                    <x-bs::input.text wire:model.defer="shipping.street_no" error="shipping.street_no" id="street-no" placeholder="{{ __('Street no') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-8">
                <x-bs::input.floating-label for="city" label="{{ __('City') }}">
                    <x-bs::input.text wire:model.defer="shipping.city" error="shipping.city" id="city" placeholder="{{ __('City') }}"/>
                </x-bs::input.floating-label>
            </div>

            <div class="col-4">
                <x-bs::input.floating-label for="postcode" label="{{ __('Postcode') }}">
                    <x-bs::input.text wire:model.defer="shipping.postcode" error="shipping.postcode" id="postcode" placeholder="{{ __('Postcode') }}"/>
                </x-bs::input.floating-label>
            </div>
        </div>
    </x-bs::card.body>
</x-bs::card>

@push('footer_scripts')
    <script>
        const container = document.getElementById('shipping-addresses')
        const collapseElementList = [].slice.call(container.querySelectorAll('.collapse'))
        collapseElementList.map(el => new bootstrap.Collapse(el, {toggle: false}))

        container.addEventListener('change', evt => {
            if (evt.target.matches('[name=selectedShipping]')) {
                const prev = container.querySelector('.collapse.show')

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
