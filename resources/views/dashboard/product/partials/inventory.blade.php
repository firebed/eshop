<div class="card shadow-sm"
     x-data="{
        is_physical: {{ old('is_physical', $product->is_physical ?? true) ? 'true' : 'false' }}
     }">
    <div class="card-body d-grid gap-3">
        <div class="fw-500">{{ __("Inventory") }}</div>

        <div class="row g-3">
            <x-bs::input.group for="sku" label="{{ __('SKU') }}" class="col">
                <x-bs::input.text name="sku" value="{{ old('sku', $product->sku ?? '') }}" error="sku" id="sku"/>
            </x-bs::input.group>

            <x-bs::input.group for="barcode" label="{{ __('Barcode') }}" class="col">
                <x-bs::input.text name="barcode" value="{{ old('barcode', $product->barcode ?? '') }}" error="barcode" id="barcode"/>
            </x-bs::input.group>
        </div>

        <x-bs::input.checkbox x-model="is_physical" name="is_physical" error="is_physical" id="physical-product">
            {{ __('eshop::product.is_physical') }}
        </x-bs::input.checkbox>

        <div x-show="is_physical" x-transition class="row row-cols-2 g-3">
            <x-bs::input.group for="location" label="{{ __('Location') }}" class="col">
                <x-bs::input.text name="location" value="{{ old('location', $product->location ?? '') }}" error="location" id="location"/>
            </x-bs::input.group>

            <x-bs::input.group x-data="{stock: {{ old('stock', $product->stock ?? 0) ?? 0 }}}" for="stock" label="{{ __('Stock') }}" class="col">
                <x-eshop::integer x-effect="stock = value" value="stock" id="stock" error="stock"/>
                <input type="text" x-model="stock" name="stock" hidden>
            </x-bs::input.group>

            <x-bs::input.group x-data="{weight: {{ old('weight', $product->weight ?? 0) ?? 0 }}}" for="weight" label="{{ __('Weight') }}" class="col">
                <x-eshop::integer x-effect="weight = value" value="weight" id="weight" error="weight"/>
                <input type="text" x-model="weight" name="weight" hidden>
            </x-bs::input.group>

            <x-bs::input.group for="unit-id" label="{{ __('Unit') }}" class="col">
                <x-bs::input.select name="unit_id" id="unit-id" error="unit_id">
                    <option value="" disabled>{{ __('eshop::unit.select') }}</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" @if($unit->id == old('unit_id', $product->unit_id ?? null)) selected @endif>
                            {{ __("eshop::unit.$unit->name") }}
                        </option>
                    @endforeach
                </x-bs::input.select>
            </x-bs::input.group>
        </div>
    </div>
</div>
