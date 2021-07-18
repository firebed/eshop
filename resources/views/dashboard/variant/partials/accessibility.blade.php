<div class="d-grid gap-2">
    <div class="fw-500 mb-3">{{ __("Accessibility") }}</div>

    <x-bs::input.checkbox name="visible" :checked="old('visible', $variant->visible ?? $product->visible)" id="visible">
        {{ __("Customers can view this product") }}
    </x-bs::input.checkbox>

    <div>
        <x-bs::input.checkbox name="available" :checked="old('available', $variant->available ?? $product->available)" id="available">
            {{ __("Customers can purchase this variant") }}
        </x-bs::input.checkbox>

        <div x-data="{number: '{{ old('available_gt', $variant->available_gt ?? $product->available_gt) }}' }" class="d-flex gap-2 align-items-baseline">
            <label for="available-gt">{{ __('Prevent purchase when stock gets less than') }}</label>
            <x-eshop::integer x-effect="number = value" :value="'number'" error="available_gt" id="available-gt" class="form-control-sm w-5r"/>
            <input x-model="number" type="text" name="available_gt" hidden>
        </div>
    </div>

    <div>
        <x-bs::input.checkbox name="display_stock" :checked="old('display_stock', $variant->display_stock ?? $product->display_stock)" id="display-stock">
            {{ __("Customers can see the available stock") }}
        </x-bs::input.checkbox>

        <div x-data="{number: '{{ old('display_stock_lt', $variant->display_stock_lt ?? $product->display_stock_lt) }}' }" class="d-flex gap-2 align-items-baseline">
            <label for="display-stock-lt">{{ __('Hide availability when stock is greater than') }}</label>
            <x-eshop::integer x-effect="number = value" :value="'number'" error="display_stock_lt" id="display-stock-lt" class="form-control-sm w-5r"/>
            <input x-model="number" type="text" name="display_stock_lt" hidden>
        </div>
    </div>
</div>