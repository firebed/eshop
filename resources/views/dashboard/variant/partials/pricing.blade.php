<div class="d-grid gap-2">
    <div class="fw-500 mb-3">{{ __("Pricing") }}</div>

    <div class="row row-cols-2 row-cols-sm-4 g-3">
        <x-bs::input.group x-data="{price:{{ old('price', $variant->price ?? $product->price ?? 0) }} }" for="selling-price" label="{{ __('eshop::product.price') }}" class="col">
            <x-eshop::money x-effect="price = value" value="price" id="selling-price" error="price"/>
            <input x-model="price" name="price" hidden/>
        </x-bs::input.group>

        <x-bs::input.group x-data="{price:{{ old('compare_price', $variant->compare_price ?? $product->compare_price ?? 0) }} }" for="compare-price" label="{{ __('eshop::product.purchase_price') }}" class="col">
            <x-eshop::money x-effect="price = value" value="price" id="compare-price" error="compare_price"/>
            <input x-model="price" name="compare_price" hidden/>
        </x-bs::input.group>

        <x-bs::input.group x-data="{discount: {{ old('discount', $variant->discount ?? $product->discount ?? 0) }} }" for="discount" label="{{ __('eshop::product.discount') }}" class="col">
            <x-eshop::percentage x-effect="discount = value" value="discount" id="discount" error="discount"/>
            <input x-model="discount" name="discount" hidden/>
        </x-bs::input.group>

        <x-bs::input.group for="vat" label="{{ __('eshop::product.tax') }}" class="col">
            <x-bs::input.select name="vat" id="vat" error="vat">
                <option value="" disabled>{{ __('eshop::vat.select') }}</option>
                @isset($vats)
                    @foreach($vats as $vat)
                        <option value="{{ $vat->regime }}" @if($vat->regime === old('vat', $variant->vat ?? $product->vat ?? 0)) selected @endif>
                            {{ __("eshop::vat.$vat->name") }} ({{ format_percent($vat->regime) }})
                        </option>
                    @endforeach
                @endisset
            </x-bs::input.select>
        </x-bs::input.group>
    </div>
</div>
