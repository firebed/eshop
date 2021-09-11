<form wire:submit.prevent="save">
    <x-bs::modal wire:model.defer="showEditingModal">
        <x-bs::modal.header>{{ __('Edit area') }}</x-bs::modal.header>
        <x-bs::modal.body>
            <div class="d-grid gap-3">
                <div class="row g-3">
                    <x-bs::input.group for="country" label="{{ __('Country') }}" class="col-6">
                        <x-bs::input.select wire:model.defer="model.country_id" error="model.country_id" id="country">
                            <option value="" disabled>{{ __('Country') }}</option>
                            @isset($countries)
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            @endisset
                        </x-bs::input.select>
                    </x-bs::input.group>

                    <x-bs::input.group for="type" label="{{ __('Type') }}" class="col-6">
                        <x-bs::input.select wire:model.defer="model.type" error="model.type" id="type">
                            <option value="" disabled>{{ __('Type') }}</option>
                            <option value="ΔΠ">ΔΠ</option>
                            <option value="ΔΧ">ΔΧ</option>
                            <option value="D1">D1</option>
                            <option value="D2">D2</option>
                            <option value="D3">D3</option>
                        </x-bs::input.select>
                    </x-bs::input.group>

                    <x-bs::input.group for="region" label="{{ __('Region') }}">
                        <x-bs::input.text wire:model.defer="model.region" error="model.region" id="region"/>
                    </x-bs::input.group>

                    <x-bs::input.group for="store" label="{{ __('Store') }}">
                        <x-bs::input.text wire:model.defer="model.courier_store" error="model.courier_store" id="store"/>
                    </x-bs::input.group>

                    <x-bs::input.group for="address" label="{{ __('Address') }}">
                        <x-bs::input.text wire:model.defer="model.courier_address" error="model.courier_address" id="address"/>
                    </x-bs::input.group>

                    <x-bs::input.group for="postcode" label="{{ __('Postcode') }}">
                        <x-bs::input.text wire:model.defer="model.postcode" error="model.postcode" id="postcode"/>
                    </x-bs::input.group>

                    <x-bs::input.group for="phone" label="{{ __('Phone') }}">
                        <x-bs::input.text wire:model.defer="model.courier_phone" error="model.courier_phone" id="phone"/>
                    </x-bs::input.group>
                </div>
            </div>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>
