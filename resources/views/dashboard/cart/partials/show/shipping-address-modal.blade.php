<form wire:submit.prevent="save">
    <x-bs::modal wire:model.defer="showModal">
        <x-bs::modal.header>{{ __('Shipping address') }}</x-bs::modal.header>
        <x-bs::modal.body>
            <div class="row row-cols-2 g-3">
                <x-bs::input.group for="contact-first-name" label="{{ __('First name') }}" class="col">
                    <x-bs::input.text wire:model.defer="shippingAddress.first_name" id="contact-first-name" error="shippingAddress.first_name"/>
                </x-bs::input.group>

                <x-bs::input.group for="contact-last-name" label="{{ __('Last name') }}" class="col">
                    <x-bs::input.text wire:model.defer="shippingAddress.last_name" id="contact-last-name" error="shippingAddress.last_name"/>
                </x-bs::input.group>

                <x-bs::input.group for="contact-phone" label="{{ __('Phone number') }}" class="col-12">
                    <x-bs::input.text wire:model.defer="shippingAddress.phone" id="contact-phone" error="shippingAddress.phone"/>
                </x-bs::input.group>

                <x-bs::input.group for="shipping-address-country" label="{{ __('Country') }}" class="col">
                    <x-bs::input.select wire:model="shippingAddress.country_id" id="shipping-address-country" error="shippingAddress.country_id">
                        <option value="">{{ __("Select country") }}</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </x-bs::input.select>
                </x-bs::input.group>

                @if(empty($provinces))
                    <x-bs::input.group for="shipping-address-province" label="{{ __('Province') }}" class="col">
                        <x-bs::input.text wire:model.defer="shippingAddress.province" id="shipping-address-province" error="shippingAddress.province"/>
                    </x-bs::input.group>
                @else
                    <x-bs::input.group for="shipping-address-province" label="{{ __('Province') }}" class="col">
                        <x-bs::input.select wire:model="shippingAddress.province" id="shipping-address-province" error="shippingAddress.province">
                            <option value="">{{ __("Select province") }}</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province }}">{{ $province }}</option>
                            @endforeach
                        </x-bs::input.select>
                    </x-bs::input.group>
                @endif

                <x-bs::input.group for="shipping-address-city" label="{{ __('City') }}" class="col">
                    <x-bs::input.text wire:model.defer="shippingAddress.city" id="shipping-address-city" error="shippingAddress.city"/>
                </x-bs::input.group>

                <x-bs::input.group for="shipping-address-postcode" label="{{ __('Postcode') }}" class="col">
                    <x-bs::input.text wire:model.defer="shippingAddress.postcode" id="shipping-address-postcode" error="shippingAddress.postcode"/>
                </x-bs::input.group>

                <x-bs::input.group for="shipping-address-street" label="{{ __('Street') }}" class="col-8">
                    <x-bs::input.text wire:model.defer="shippingAddress.street" id="shipping-address-street" error="shippingAddress.street"/>
                </x-bs::input.group>

                <x-bs::input.group for="shipping-address-street-no" label="{{ __('No') }}" class="col-4">
                    <x-bs::input.text wire:model.defer="shippingAddress.street_no" id="shipping-address-street-no" error="shippingAddress.street_no"/>
                </x-bs::input.group>
            </div>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>
