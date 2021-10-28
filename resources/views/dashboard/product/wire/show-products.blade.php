<div class="col-12 p-3 p-xl-4 d-grid gap-3">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex gap-2">
            <div class="small text-secondary">
                <a href="{{ route('products.index') }}" class="text-decoration-none">{{ __("All") }} <span class="text-secondary">({{ $productsCount }})</span></a>
            </div>

            <div class="border-end"></div>

            <div class="small text-secondary">
                <a href="{{ route('products.trashed.index') }}" class="text-decoration-none">{{ __("Trash") }} <span class="text-secondary">({{ $trashCount }})</span></a>
            </div>
        </div>

        <a href="{{ route('products.create') }}" class="ms-auto btn btn-primary">
            <em class="fa fa-plus me-2"></em>{{ __('eshop::product.new_product') }}
        </a>
    </div>

    <div class="row row-cols-1 row-cols-md-2 g-3">
        <div class="col">
            <x-bs::input.search wire:model="name" placeholder="{{ __('eshop::product.search')}}"/>
        </div>

        <div class="col d-flex gap-3">
            <x-bs::input.select wire:model="category">
                <option value="" disabled>{{ __("eshop::product.category") }}</option>
                @foreach($this->categories as $parentId => $group)
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

            <x-bs::input.select wire:model="manufacturer">
                <option value="" disabled>{{ __("eshop::product.manufacturer") }}</option>
                @foreach($this->manufacturers as $manufacturer)
                    <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                @endforeach
            </x-bs::input.select>

            <x-bs::button.white wire:click="clearSelections" wire:loading.attr="disabled" wire:target="clearSelections">
                <em class="fas fa-brush"></em>
            </x-bs::button.white>
        </div>
    </div>

    <x-bs::card>
        <div class="table-responsive">
            @include('eshop::dashboard.product.partials.products-table')
        </div>
    </x-bs::card>
</div>
