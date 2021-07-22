<div class="card shadow-sm" style="background-color: rgb(248, 251, 252)">
    <div class="card-body">
        <div class="fw-500 mb-3">{{ __("Organization") }}</div>

        <div class="d-grid gap-3">
            <x-bs::input.group for="category" label="{{ __('Category') }}">
                <x-bs::input.select x-on:change="$dispatch('product-category-changed', $el.value)" name="category_id" error="category_id" id="category" required>
                    <option value="" disabled selected>{{ __('Select category') }}</option>
                    @foreach($categories as $parentId => $group)
                        @if($parentId)
                            <optgroup label="{{ $group->first()->parent->name }}">
                                @foreach($group as $category)
                                    <option value="{{ $category->id }}" @if($category->id == old('category_id', $product->category_id ?? NULL)) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </optgroup>
                        @else
                            @foreach($group as $category)
                                <option value="{{ $category->id }}" @if($category->id == old('category_id', $product->category_id ?? NULL)) selected @endif>{{ $category->name }}</option>
                            @endforeach
                        @endif
                    @endforeach
                </x-bs::input.select>
            </x-bs::input.group>

            <x-bs::input.group for="manufacturer" label="{{ __('Manufacturer') }}">
                <x-bs::input.select name="manufacturer_id" error="manufacturer_id" id="manufacturer">
                    <option value="" disabled selected>{{ __('Select manufacturer') }}</option>
                    @foreach($manufacturers as $manufacturer)
                        <option value="{{ $manufacturer->id }}" @if($manufacturer->id == old('manufacturer_id', $product->manufacturer_id ?? NULL)) selected @endif()>{{ $manufacturer->name }}</option>
                    @endforeach
                </x-bs::input.select>
            </x-bs::input.group>

            <x-bs::input.group for="collections" label="{{ __('eshop::product.collections') }}">
                <select
                        x-init="new SlimSelect({select: $el, showSearch: false, allowDeselect: true })"
                        name="collections[]"
                        hidden
                        id="collections"
                        multiple>
                    >
                    <option data-placeholder="true"></option>
                    @foreach($collections as $collection)
                        <option value="{{ $collection->id }}" @if(in_array($collection->id, old('collections', isset($product) ? $product->collections->pluck('id')->all() : []))) selected @endif>{{ $collection->name }}</option>
                    @endforeach
                </select>
            </x-bs::input.group>
        </div>
    </div>
</div>
