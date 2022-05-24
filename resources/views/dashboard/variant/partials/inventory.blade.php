<div class="d-grid gap-3"
     x-data="{
        is_physical: {{ old('is_physical', $variant->is_physical ?? $product->is_physical ?? TRUE) ? 'true' : 'false' }}
     }">
    <div class="fw-500">{{ __("Inventory") }}</div>

    <div class="row row-cols-2 row-cols-sm-3 g-3">
        <x-bs::input.group for="sku" label="{{ __('SKU') }}" class="col">
            @if(request()->routeIs('products.variants.create'))
                <x-bs::input.text x-on:variant-options-updated.window="$el.value = '{{ $product->sku }}' + '-' + slugify($event.detail.join('-'))" value="{{ old('sku', $variant->sku ?? ($product->sku . '-') ?? '') }}" name="sku" id="sku" error="sku" required/>
            @else
                <x-bs::input.text value="{{ old('sku', $variant->sku ?? ($product->sku . '-') ?? '') }}" name="sku" id="sku" error="sku" required/>
            @endif
        </x-bs::input.group>

        <x-bs::input.group for="mpn" label="{{ __('MPN') }}" class="col">
            <x-bs::input.text name="mpn" value="{{ old('mpn', $variant->mpn ?? '') }}" error="mpn" id="mpn"/>
        </x-bs::input.group>

        <x-bs::input.group for="barcode" label="{{ __('Barcode') }}" class="col flex-grow-1">
            <div x-data="{
                        product_id: {{ $product->id }},
                        variant_id: {{ $variant->id ?? 'null' }},
                        generate: function() {
                            const params = {
                                product_id: this.product_id,
                                variant_id: this.variant_id,
                            }
        
                            axios.post('{{ route('products.barcode.create') }}', params)
                                .then(r => this.$refs.barcode.value = r.data)
                        }
                    }"
                 class="input-group mb-3">
                <input x-ref="barcode" value="{{ old('barcode', $variant->barcode ?? '') }}" type="text" class="form-control" name="barcode" id="barcode" placeholder="{{ __('Barcode') }}">
                <button x-on:click.prevent="generate()" class="btn btn-outline-secondary" type="button" id="button-addon2"><em class="fas fa-key"></em></button>
                @error('barcode')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </x-bs::input.group>
    </div>

    <x-bs::input.checkbox x-model="is_physical" name="is_physical" error="is_physical" id="physical-product">
        {{ __('eshop::product.is_physical') }}
    </x-bs::input.checkbox>

    <div x-show="is_physical" x-transition class="row row-cols-2 row-cols-md-4 g-3">
        <x-bs::input.group for="location" label="{{ __('Location') }}" class="col">
            <x-bs::input.text name="location" value="{{ old('location', $variant->location ?? $product->location) }}" id="location" error="location"/>
        </x-bs::input.group>

        <x-bs::input.group x-data="{stock: {{ old('stock', $variant->stock ?? 0) ?? 0 }}}" for="stock" label="{{ __('Stock') }}" class="col">
            <div class="input-group">
                <input type="number" step="1" class="form-control" x-model="stock" id="stock" name="stock">
                <button @click.prevent="stock -= (parseInt(prompt('Αφαίρεση ποσότητας:')) || 0)" class="btn btn-outline-secondary"><em class="fas fa-minus"></em></button>
                <button @click.prevent="stock += (parseInt(prompt('Προσθήκη ποσότητας:')) || 0)" class="btn btn-outline-secondary"><em class="fas fa-plus"></em></button>
            </div>
        </x-bs::input.group>

        <div x-data="{weight: 0}">
            <x-bs::input.group x-data="{ weight: {{ old('weight', $variant->weight ?? $product->weight ?? 0) ?? 0 }} }" for="weight" label="{{ __('Weight') }}" class="col">
                <x-eshop::integer x-effect="weight = value" value="weight" error="weight" id="weight" currencySymbol=" gr"/>
                <input x-model="weight" name="weight" type="text" hidden>
            </x-bs::input.group>
        </div>

        <x-bs::input.group for="unit" label="{{ __('Unit') }}" class="col">
            <x-bs::input.select name="unit_id" id="unit" error="unit_id">
                <option value="" disabled>{{ __('Select unit') }}</option>
                @isset($units)
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" @if($unit->id == old('unit_id', $variant->unit_id ?? $product->unit_id)) selected @endif>
                            {{ __("eshop::unit.$unit->name") }}
                        </option>
                    @endforeach
                @endisset
            </x-bs::input.select>
        </x-bs::input.group>
    </div>
</div>
