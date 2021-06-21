<div class="card shadow-sm">
    <div class="card-body">
        <div class="fs-5 mb-3">{{ __("Accessibility") }}</div>
        <div class="d-grid gap-2">
            <x-bs::input.checkbox wire:model.defer="product.visible" id="visible">
                {{ __("Customers can view this product") }}
            </x-bs::input.checkbox>

            <div>
                <x-bs::input.checkbox wire:model.defer="product.available" id="available">
                    {{ __("Customers can purchase this product") }}
                </x-bs::input.checkbox>

                <x-bs::input.group for="available-gt" label="{{ __('Prevent purchase when stock gets less than') }}" label-col="9" class="ps-4" inline>
                    <x-bs::input.integer wire:model.defer="product.available_gt" id="available-gt" class="form-control-sm"/>
                </x-bs::input.group>
            </div>

            <div>
                <x-bs::input.checkbox wire:model.defer="product.display_stock" id="display-stock">
                    {{ __("Customers can see the available stock") }}
                </x-bs::input.checkbox>

                <x-bs::input.group for="display-stock-lt" label="{{ __('Hide availability when stock is greater than') }}" label-col="9" class="ps-4" inline>
                    <x-bs::input.integer wire:model.defer="product.display_stock_lt" class="form-control-sm"/>
                </x-bs::input.group>
            </div>
        </div>
    </div>
</div>
