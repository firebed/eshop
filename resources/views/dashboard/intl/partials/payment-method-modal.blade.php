<form wire:submit.prevent="save">
    <x-bs::modal wire:model.defer="showEditingModal">
        <x-bs::modal.header>{{ __('Edit payment method') }}</x-bs::modal.header>
        <x-bs::modal.body>
            <div class="d-grid gap-3">
                <div class="d-flex justify-content-end">
                    <x-bs::input.switch wire:model.defer="model.visible" id="visible">{{ __('Visible') }}</x-bs::input.switch>
                </div>

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

                    <x-bs::input.group for="method" label="{{ __('Method') }}" class="col-6">
                        <x-bs::input.select wire:model.defer="model.payment_method_id" error="model.payment_method_id" id="method">
                            <option value="" disabled>{{ __('Payment method') }}</option>
                            @isset($methods)
                                @foreach($methods as $method)
                                    <option value="{{ $method->id }}">{{ __("eshop::payment.$method->name") }}</option>
                                @endforeach
                            @endisset
                        </x-bs::input.select>
                    </x-bs::input.group>

                    <x-bs::input.group for="position" label="{{ __('Position') }}" class="col-4">
                        <x-bs::input.integer wire:model.defer="model.position" error="model.position" id="position"/>
                    </x-bs::input.group>

                    <x-bs::input.group for="fee" label="{{ __('Fee') }}" class="col-4">
                        <x-bs::input.money wire:model.defer="model.fee" error="model.fee" id="fee"/>
                    </x-bs::input.group>

                    <x-bs::input.group for="cart-total" label="{{ __('Minimum order total') }}" class="col-4">
                        <x-bs::input.money wire:model.defer="model.cart_total" error="model.cart_total" id="cart-total"/>
                    </x-bs::input.group>
                </div>

                <x-bs::input.group for="description" label="{{ __('Description') }}">
                    <x-bs::input.textarea wire:model.defer="description" error="description" />
{{--                    <x-bs::input.rich-text wire:model.defer="description" error="description" plugins="lists" toolbar="fontselect | bold italic underline | forecolor | bullist numlist"/>--}}
                </x-bs::input.group>
            </div>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>
