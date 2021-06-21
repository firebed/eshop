<form wire:submit.prevent="save">
    <x-bs::modal wire:model.defer="showModal">
        <x-bs::modal.header>{{ __('Billing address') }}</x-bs::modal.header>
        <x-bs::modal.body>
            <div class="row row-cols-2 g-3" x-data="{ sameAsShipping: @entangle('sameAsShipping').defer }">
                <div class="col-12">
                    <x-bs::input.checkbox x-model="sameAsShipping" id="same-as-shipping">
                        {{ __("Same as shipping address") }}
                    </x-bs::input.checkbox>
                </div>

                <x-bs::input.group for="billing-address-country" label="{{ __('Country') }}" class="col" x-show="!sameAsShipping">
                    <x-bs::input.select wire:model.defer="billingAddress.country_code" id="billing-address-country" error="billingAddress.country_code">
                        <option value="" disabled>{{ __("Select country") }}</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->code }}">{{ $country->name }}</option>
                        @endforeach
                    </x-bs::input.select>
                </x-bs::input.group>

                <x-bs::input.group for="billing-address-province" label="{{ __('Province') }}" class="col" x-show="!sameAsShipping">
                    <x-bs::input.text wire:model.defer="billingAddress.province" id="billing-address-province" error="billingAddress.province"/>
                </x-bs::input.group>

                <x-bs::input.group for="billing-address-city" label="{{ __('City') }}" x-show="!sameAsShipping">
                    <x-bs::input.text wire:model.defer="billingAddress.city" id="billing-address-city" error="billingAddress.city"/>
                </x-bs::input.group>

                <x-bs::input.group for="billing-address-postcode" label="{{ __('Postcode') }}" class="col" x-show="!sameAsShipping">
                    <x-bs::input.text wire:model.defer="billingAddress.postcode" id="billing-address-postcode" error="billingAddress.postcode"/>
                </x-bs::input.group>

                <x-bs::input.group for="billing-address-street" label="{{ __('Street') }}" class="col-12" x-show="!sameAsShipping">
                    <x-bs::input.text wire:model.defer="billingAddress.street" id="billing-address-street" error="billingAddress.street"/>
                </x-bs::input.group>
            </div>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>
