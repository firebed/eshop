<form wire:submit.prevent="save">
    <x-bs::modal wire:model.defer="showModal" size="xl">
        <x-bs::modal.header>{{ __('Edit variant') }}</x-bs::modal.header>
        <x-bs::modal.body>
            <div class="d-grid gap-3">
                <div class="row g-3">
                    <div class="col-12 col-xl-6 d-grid gap-3">
                        @include('eshop::dashboard.product-variant.partials.options')

                        @include('eshop::dashboard.product-variant.partials.pricing')
                    </div>

                    <div class="col-12 col-xl-6 d-grid gap-3">
                        @include('eshop::dashboard.product-variant.partials.inventory')

                        <div class="fw-500">{{ __("Accessibility") }}</div>

                        <div class="d-grid gap-2">
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
                </div>

                <x-bs::input.group for="description" label="{{ __('Description') }}">
                    <x-bs::input.rich-text wire:model.defer="description" error="description" plugins="lists" toolbar="fontselect | bold italic underline | forecolor | bullist numlist | removeformat"/>
                </x-bs::input.group>
            </div>
        </x-bs::modal.body>
        <x-bs::modal.footer>
            <x-bs::modal.close-button>{{ __('Cancel') }}</x-bs::modal.close-button>
            <x-bs::button.primary type="submit" wire:loading.attr="disabled">
                <em wire:loading class="fa fa-spinner fa-spin me-2"></em>
                <span wire:loading wire:target="image">{{ __("Uploading") }}</span>
                <span wire:loading.remove wire:target="image">{{ __("Save") }}</span>
            </x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>
