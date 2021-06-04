<div class="card shadow-sm" style="background-color: rgb(248, 251, 252)">
    <div class="card-body">
        <div class="fs-5 mb-3">{{ __("Organization") }}</div>
        <div class="d-grid gap-3">
            <x-bs::input.group for="category" label="{{ __('Category') }}">
                <x-bs::input.select wire:model="product.category_id" id="category" error="product.category_id">
                    <option value="" disabled>{{ __('Select category') }}</option>
                    @foreach($categories as $parentId => $group)
                        @if($parentId)
                            <optgroup label="{{ $group->first()->parent->name }}">
                                @foreach($group as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </optgroup>
                        @else
                            @foreach($group as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        @endif
                    @endforeach
                </x-bs::input.select>
            </x-bs::input.group>

            <x-bs::input.group for="manufacturer" label="{{ __('Manufacturer') }}">
                <x-bs::input.select wire:model.defer="product.manufacturer_id" id="manufacturer" error="product.manufacturer_id">
                    <option value="" disabled>{{ __('Select manufacturer') }}</option>
                    @foreach($manufacturers as $manufacturer)
                        <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                    @endforeach
                </x-bs::input.select>
            </x-bs::input.group>
        </div>
    </div>
</div>
