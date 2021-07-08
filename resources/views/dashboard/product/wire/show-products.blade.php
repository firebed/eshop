<div class="col-12 p-4 d-grid gap-3">
    <div class="d-grid gap-2">
        <div class="d-flex">
            <div class="small text-secondary">
                <a href="{{ route('products.index') }}" class="text-decoration-none">{{ __("All") }} <span class="text-secondary">({{ $productsCount }})</span></a>
            </div>
            <div class="border-end me-2 ps-2"></div>
            <div class="small text-secondary">
                <a href="{{ route('products.trashed.index') }}" class="text-decoration-none">{{ __("Trash") }} <span class="text-secondary">({{ $trashCount }})</span></a>
            </div>
        </div>

        <h1 class="fs-3 mb-0">{{ __("eshop::product.products") }}</h1>
    </div>

    <div class="row gx-2">
        <div class="col d-flex gap-2">
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

            <x-bs::input.search wire:model="name" placeholder="{{ __('eshop::product.search')}}"/>
            <x-bs::button.white wire:click="clearSelections" wire:loading.attr="disabled" wire:target="clearSelections">{{ __("Clear") }}</x-bs::button.white>
        </div>

        <div class="col d-flex justify-content-end">
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <em class="fa fa-plus me-2"></em>{{ __('eshop::product.new_product') }}
            </a>
        </div>
    </div>

    <x-bs::card>
        <div class="table-responsive">
            @include('eshop::dashboard.product.partials.products-table')
        </div>
    </x-bs::card>
</div>
