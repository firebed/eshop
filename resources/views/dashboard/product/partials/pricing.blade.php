<div class="card shadow-sm">
    <div class="card-body">
        <div class="fw-500 mb-3">{{ __("Pricing") }}</div>

        <div class="row row-cols-1 row-cols-sm-3 g-3">
            <x-bs::input.group x-data="{ price: {{ old('price', $product->price ?? 0) ?? 0 }} }" for="selling-price" label="{{ __('Selling price') }}" class="col">
                <x-eshop::money x-effect="price = value" value="price" id="selling-price" error="price"/>
                <input type="text" x-model="price" name="price" hidden>
            </x-bs::input.group>

            <x-bs::input.group x-data="{ price: {{ old('compare_price', $product->compare_price ?? 0) ?? 0 }} }" for="compare-price" label="{{ __('eshop::product.purchase_price') }}" class="col">
                <x-eshop::money x-effect="price = value" value="price" id="compare-price" error="compare_price"/>
                <input type="text" x-model="price" name="compare_price" hidden>
            </x-bs::input.group>

            <x-bs::input.group x-data="{ price: {{ old('wholesale_price', $product->wholesale_price ?? 0) ?? 0 }} }" for="wholesale-price" label="{{ __('eshop::product.wholesale_price') }}" class="col">
                <x-eshop::money x-effect="price = value" value="price" id="wholesale-price" error="wholesale_price"/>
                <input type="text" x-model="price" name="wholesale_price" hidden>
            </x-bs::input.group>
        </div>

        <div class="row row-cols-2 g-3">
            <x-bs::input.group x-data="{ discount: {{ old('discount', $product->discount ?? 0) ?? 0 }} }" for="discount" label="{{ __('Discount') }}" class="col">
                <x-eshop::percentage x-effect="discount = value" value="discount" name="discount" id="discount" error="discount"/>
                <input type="text" x-model="discount" name="discount" hidden>
            </x-bs::input.group>

            <x-bs::input.group for="vat" label="{{ __('Vat') }}" class="col">
                <x-bs::input.select name="vat" error="vat" id="vat">
                    <option value="" disabled>{{ __('eshop::vat.select') }}</option>
                    @foreach($vats as $vat)
                        <option value="{{ $vat->regime }}" @if($vat->regime === old('vat', $product->vat ?? null)) selected @endif>
                            {{ __("eshop::vat.$vat->name") }} ({{ format_percent($vat->regime) }})
                        </option>
                    @endforeach
                </x-bs::input.select>
            </x-bs::input.group>
        </div>
    </div>
</div>
