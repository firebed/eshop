<form wire:submit.prevent="save">
    <x-bs::modal wire:model.defer="showModal">
        <x-bs::modal.header>{{ __('Edit variant') }}</x-bs::modal.header>
        <x-bs::modal.body>
            <div class="d-grid gap-3">
                @include('eshop::dashboard.product-variant.partials.options')

                @include('eshop::dashboard.product-variant.partials.pricing')
                @include('eshop::dashboard.product-variant.partials.inventory')

                <div class="d-grid gap-2">
                    <div class="fw-500">{{ __("Accessibility") }}</div>

                    <x-bs::input.checkbox wire:model.defer="variant.visible" id="visible">
                        {{ __("Customers can view this product") }}
                    </x-bs::input.checkbox>

                    <div>
                        <x-bs::input.checkbox wire:model.defer="variant.available" id="available">
                            {{ __("Customers can purchase this variant") }}
                        </x-bs::input.checkbox>

                        <x-bs::input.group for="available-gt" label="{{ __('Prevent purchase when stock gets less than') }}" label-col="9" class="ps-4" inline>
                            <x-bs::input.integer wire:model.defer="variant.available_gt" id="available-gt" class="form-control-sm"/>
                        </x-bs::input.group>
                    </div>

                    <div>
                        <x-bs::input.checkbox wire:model.defer="variant.display_stock" id="display-stock">
                            {{ __("Customers can see the available stock") }}
                        </x-bs::input.checkbox>

                        <x-bs::input.group for="display-stock-lt" label="{{ __('Hide availability when stock is greater than') }}" label-col="9" class="ps-4" inline>
                            <x-bs::input.integer wire:model.defer="variant.display_stock_lt" class="form-control-sm"/>
                        </x-bs::input.group>
                    </div>
                </div>
            </div>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">{{ __("Save") }}</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>
