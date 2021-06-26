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

        <h1 class="fs-3 mb-0">{{ __("Trashed products") }}</h1>
    </div>

    <div class="d-flex gap-2">
        <x-bs::input.select wire:model="category">
            <option value="" disabled>{{ __("Filter by category") }}</option>
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
            <option value="" disabled>{{ __("Filter by manufacturer") }}</option>
            @foreach($this->manufacturers as $manufacturer)
                <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
            @endforeach
        </x-bs::input.select>

        <x-bs::input.search wire:model="name" placeholder="{{ __('Filter by name')}}"/>
        <x-bs::button.danger wire:click="confirmDelete()" wire:loading.attr="disabled" wire:target="confirmDelete" class="text-nowrap">{{ __("Delete permanently") }}</x-bs::button.danger>
    </div>

    <x-bs::card>
        <div class="table-responsive">
            @include('eshop::dashboard.product.trashed.partials.trashed-products-table')
        </div>
    </x-bs::card>

    @include('eshop::dashboard.product.trashed.partials.delete-confirmation-modal')
</div>
