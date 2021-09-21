<div class="card shadow-sm">
    <div class="card-body">
        <div class="fw-500 mb-3">{{ __("Accessibility") }}</div>

        <div class="d-grid gap-2">
            <x-bs::input.checkbox name="visible" :checked="old('visible', $product->visible ?? true)" id="visible">
                {{ __("Customers can view this product") }}
            </x-bs::input.checkbox>

            <div>
                <x-bs::input.checkbox name="available" :checked="old('available', $product->available ?? true)" id="available">
                    {{ __("Customers can purchase this product") }}
                </x-bs::input.checkbox>

                <x-bs::input.group x-data="{number: '{{ old('available_gt', $product->available_gt ?? 0) ?? 0 }}' }" for="available-gt" label="{{ __('Prevent purchase when stock gets less than') }}" label-col="9" class="ps-4" inline>
                    <x-eshop::integer x-effect="number = value" :value="'number'" id="available-gt" class="form-control-sm"/>
                    <input type="text" x-model="number" name="available_gt" hidden>
                </x-bs::input.group>
            </div>

            <div>
                <x-bs::input.checkbox name="display_stock" :checked="old('display_stock', $product->display_stock ?? true)" id="display-stock">
                    {{ __("Customers can see the available stock") }}
                </x-bs::input.checkbox>

                <x-bs::input.group x-data="{number: '{{ old('display_stock_lt', $product->display_stock_lt ?? '') }}' }" for="display-stock-lt" label="{{ __('Hide availability when stock is greater than') }}" label-col="9" class="ps-4" inline>
                    <x-eshop::integer x-effect="number = value" :value="'number'" id="display-stock-gt" class="form-control-sm"/>
                    <input type="text" x-model="number" name="display_stock_lt" hidden>
                </x-bs::input.group>
            </div>

            <x-bs::input.checkbox name="recent" :checked="old('recent', $product->recent ?? true)" id="recent">
                {{ __("Display new label") }}
            </x-bs::input.checkbox>
        </div>
    </div>
</div>
