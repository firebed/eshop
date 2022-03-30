<div class="d-grid gap-1">
    <div class="fw-500 mb-3">{{ __("Accessibility") }}</div>

    <div class="d-grid">
        <x-bs::input.switch name="visible" :checked="old('visible', $variant->visible ?? true)" id="visible">
            {{ __("Customers can view this product") }}
        </x-bs::input.switch>

        <x-bs::input.switch name="available" :checked="old('available', $variant->available ?? true)" id="available">
            {{ __("Customers can purchase this product") }}
        </x-bs::input.switch>

        <x-bs::input.switch name="display_stock" :checked="old('display_stock', $variant->display_stock ?? true)" id="display-stock">
            {{ __("Customers can see the available stock") }}
        </x-bs::input.switch>

        <x-bs::input.switch name="recent" :checked="old('recent', $variant->recent ?? false)" id="recent">
            {{ __("Display new label") }}
        </x-bs::input.switch>

        <x-bs::input.switch name="has_watermark" :checked="old('has_watermark', $variant->has_watermark ?? false)" id="has-watermark">
            {{ __("Εμφάνιση αρχικής εικόνας με watermark") }}
        </x-bs::input.switch>
        
        <div class="row mt-3">
            <div x-data="{ number: '{{ old('available_gt', isset($variant) ? $variant->available_gt : 0) }}' }" class="col">
                <label for="available-gt" class="form-label">Ελάχιστο απόθεμα</label>
                <x-eshop::integer x-effect="number = value" :value="'number'" id="available-gt" class="form-control-sm"/>
                <input type="hidden" x-model="number" name="available_gt">
            </div>

            <div x-data="{ number: '{{ old('display_stock_lt', $variant->display_stock_lt ?? '') }}' }" class="col">
                <label for="display-stock-lt" class="form-label">Απόκρυψη αποθέματος</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text fw-500">&#8805;</span>
                    <x-eshop::integer x-effect="number = value" :value="'number'" id="display-stock-lt"/>
                </div>

                <input type="hidden" x-model="number" name="display_stock_lt">
            </div>
        </div>
    </div>
</div>