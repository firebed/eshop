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

    <div class="row g-3">
        <div class="col-12 col-md-4 col-lg-4 col-xl-3 d-flex gap-2">
            <x-bs::input.search wire:model="name" placeholder="{{ __('eshop::product.search')}}"/>
            <x-bs::input.search wire:model="sku" placeholder="SKU"/>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-2">            
            <x-bs::input.select wire:model="category">
                <option value="">{{ __("eshop::product.category") }}</option>
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
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-2">
            <x-bs::input.select wire:model="manufacturer">
                <option value="">{{ __("eshop::product.manufacturer") }}</option>
                @foreach($this->manufacturers as $manufacturer)
                    <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                @endforeach
            </x-bs::input.select>
        </div>

        <div class="col d-flex justify-content-end gap-1 flex-wrap flex-xl-nowrap col-xl-5">
            <x-bs::input.select wire:model="perPage" class="w-auto">
                @for($i=10; $i <= 50; $i += 10)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </x-bs::input.select>
            
            <x-bs::input.select wire:model="visible" class="w-auto">
                <option value="">Ορατά + Κρυφά</option>
                <option value="1">Μόνο ορατά</option>
                <option value="0">Μόνο κρυφά</option>
            </x-bs::input.select>
            
            <x-bs::dropdown class="dropdown-menu-end">
                <x-bs::dropdown.button id="product-options" class="bg-white border">Επιλογές</x-bs::dropdown.button>
                <x-bs::dropdown.menu class="shadow" button="product-options">
                    <x-bs::dropdown.item wire:click="makeVisible(true)"><em class="fas fa-eye w-2r text-muted"></em>Αλλαγή σε ορατό</x-bs::dropdown.item>
                    <x-bs::dropdown.item wire:click="makeVisible(false)"><em class="fas fa-eye-slash w-2r text-muted"></em>Αλλαγή σε μη ορατό</x-bs::dropdown.item>

                    @if(eshop('skroutz'))
                        <x-bs::dropdown.divider/>
                    
                        <x-bs::dropdown.item wire:click="toggleSkroutz(true)">
                            <em class="fab fa-redhat me-2 text-orange-500 w-1r"></em>
                            Προσθήκη στο Skroutz
                        </x-bs::dropdown.item>

                        <x-bs::dropdown.item wire:click="toggleSkroutz(false)">
                            <em class="fab fa-redhat me-2 text-secondary w-1r"></em>
                            Αφαίρεση από το Skroutz
                        </x-bs::dropdown.item>
                    @endif
                    
                </x-bs::dropdown.menu>
            </x-bs::dropdown>
        </div>

        {{--            <x-bs::button.white wire:click="clearSelections" wire:loading.attr="disabled" wire:target="clearSelections">--}}
        {{--                <em class="fas fa-brush"></em>--}}
        {{--            </x-bs::button.white>--}}
    </div>

    <x-bs::card>
        <div class="table-responsive">
            @include('eshop::dashboard.product.partials.products-table')
        </div>
    </x-bs::card>
</div>
