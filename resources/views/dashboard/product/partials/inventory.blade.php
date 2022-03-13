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
                <div x-data="{
                        category_id: {{ $product->category_id ?? 'null' }},
                        product_id: {{ $product->id ?? 'null' }},
                        generate: function() {
                            const params = {
                                category_id: this.category_id,
                                product_id: this.product_id
                            }
        
                            axios.post('{{ route('products.barcode.create') }}', params)
                                .then(r => this.$refs.barcode.value = r.data)
                        }
                    }" 
                     x-on:product-category-changed.window="category_id = $event.detail.id" class="input-group mb-3">
                    <input x-ref="barcode" value="{{ old('barcode', $product->barcode ?? '') }}" type="text" class="form-control" name="barcode" id="barcode" placeholder="{{ __('Barcode') }}">
                    <button x-on:click.prevent="generate()" class="btn btn-outline-secondary" type="button" id="button-addon2"><em class="fas fa-key"></em></button>
                    @error('barcode')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </x-bs::input.group>

            <x-bs::input.group for="mpn" label="{{ __('MPN') }}" class="col">
                <x-bs::input.text name="mpn" value="{{ old('mpn', $product->mpn ?? '') }}" error="mpn" id="mpn"/>
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
                <div class="input-group">
                    <input type="number" step="1" class="form-control" x-model="stock" id="stock" name="stock">
                    <button @click.prevent="stock -= (parseInt(prompt('Αφαίρεση ποσότητας:')) || 0)" class="btn btn-outline-secondary"><em class="fas fa-minus"></em></button>
                    <button @click.prevent="stock += (parseInt(prompt('Προσθήκη ποσότητας:')) || 0)" class="btn btn-outline-secondary"><em class="fas fa-plus"></em></button>
                </div>
            </x-bs::input.group>

            <x-bs::input.group x-data="{weight: {{ old('weight', $product->weight ?? 0) ?? 0 }}}" for="weight" label="{{ __('Weight') }}" class="col">
                <x-eshop::integer x-effect="weight = value" value="weight" id="weight" error="weight" currencySymbol=" gr"/>
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