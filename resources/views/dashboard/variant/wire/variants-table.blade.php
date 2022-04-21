<div class="d-grid gap-3">
    <div class="d-flex gap-2" x-data>
        <x-bs::input.search wire:model="search" placeholder="{{ __('Search') }}"/>

        <x-bs::dropdown wire:ignore>
            <x-bs::dropdown.button class="btn-white" id="bulk-actions"><em class="fas fa-bars"></em></x-bs::dropdown.button>
            <x-bs::dropdown.menu class="shadow" button="bulk-actions" alignment="right" style="max-height: 350px; overflow-y: auto; overflow-x: hidden">
                <li class="p-0">
                    <div class="dropdown-item p-0">
                        @include('eshop::dashboard.variant.partials.variant-bulk-edit-menu-item')
                    </div>
                </li>

                <x-bs::dropdown.divider/>

                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#variant-bulk-image-modal">
                    <em class="fa fa-image me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.bulk-actions.image') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.item x-on:click.prevent="$wire.addWatermark([...document.querySelectorAll('#variants-table tbody input:checked')].map(i => i.value))">
                    <em class="far fa-copyright  me-2 text-secondary w-1r"></em>
                    Προσθήκη Watermark
                </x-bs::dropdown.item>

                <x-bs::dropdown.item x-on:click.prevent="$wire.removeWatermark([...document.querySelectorAll('#variants-table tbody input:checked')].map(i => i.value))">
                    <em class="far fa-copyright  me-2 text-secondary w-1r"></em>
                    Αφαίρεση Watermark
                </x-bs::dropdown.item>

                <x-bs::dropdown.divider/>

                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#variant-bulk-edit-modal" data-property="price" data-title="{{ __('eshop::variant.bulk-actions.price') }}">
                    <em class="fa fa-tag me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.bulk-actions.price') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#variant-bulk-edit-modal" data-property="compare_price" data-title="{{ __('eshop::variant.bulk-actions.compare_price') }}">
                    <em class="fa fa-tag me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.bulk-actions.compare_price') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#variant-bulk-edit-modal" data-property="discount" data-title="{{ __('eshop::variant.bulk-actions.discount') }}">
                    <em class="fa fa-percent me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.bulk-actions.discount') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.divider/>

                <x-bs::dropdown.item x-on:click.prevent="$wire.toggleVisible([...document.querySelectorAll('#variants-table tbody input:checked')].map(i => i.value), true)">
                    <em class="fa fa-eye me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.bulk-actions.make_visible') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.item x-on:click.prevent="$wire.toggleVisible([...document.querySelectorAll('#variants-table tbody input:checked')].map(i => i.value), false)">
                    <em class="fa fa-eye-slash me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.bulk-actions.make_hidden') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#variant-bulk-edit-modal" data-property="available_gt" data-title="{{ __('eshop::variant.bulk-actions.available_gt') }}">
                    <em class="fa fa-boxes me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.bulk-actions.available_gt') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#variant-bulk-edit-modal" data-property="display_stock_lt" data-title="{{ __('eshop::variant.bulk-actions.display_stock_lt') }}">
                    <em class="fa fa-eye-slash me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.bulk-actions.display_stock_lt') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.divider/>

                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#variant-bulk-edit-modal" data-property="sku" data-title="{{ __('eshop::variant.bulk-actions.sku') }}">
                    <em class="fa fa-box-open me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.bulk-actions.sku') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#variant-bulk-edit-modal" data-property="mpn" data-title="{{ __('eshop::variant.bulk-actions.mpn') }}">
                    <em class="fa fa-box-open me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.bulk-actions.mpn') }}
                </x-bs::dropdown.item>
                
                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#variant-bulk-edit-modal" data-property="stock" data-title="{{ __('eshop::variant.bulk-actions.stock') }}">
                    <em class="fa fa-battery-half me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.bulk-actions.stock') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#variant-bulk-edit-modal" data-property="weight" data-title="{{ __('eshop::variant.bulk-actions.weight') }}">
                    <em class="fa fa-weight-hanging me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.bulk-actions.weight') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.divider/>

                @if(eshop('skroutz'))
                    <x-bs::dropdown.item x-on:click.prevent="$wire.toggleSkroutz([...document.querySelectorAll('#variants-table tbody input:checked')].map(i => i.value), true)">
                        <em class="fab fa-redhat me-2 text-orange-500 w-1r"></em>
                        Προσθήκη στο Skroutz
                    </x-bs::dropdown.item>

                    <x-bs::dropdown.item x-on:click.prevent="$wire.toggleSkroutz([...document.querySelectorAll('#variants-table tbody input:checked')].map(i => i.value), false)">
                        <em class="fab fa-redhat me-2 text-secondary w-1r"></em>
                        Αφαίρεση από το Skroutz
                    </x-bs::dropdown.item>

                    <x-bs::dropdown.divider/>
                @endif

                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#variant-bulk-delete-modal">
                    <em class="far fa-trash-alt me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.buttons.delete') }}
                </x-bs::dropdown.item>
            </x-bs::dropdown.menu>
        </x-bs::dropdown>

        <div class="btn-group">
            <a href="{{ route('products.variants.create', $product) }}" class="btn btn-primary align-items-center"><em class="fa fa-plus"></em></a>

            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>

            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('variants.bulk-create', $product) }}"><em class="fa fa-folder-plus me-2"></em> {{ __("eshop::variant.buttons.add_many") }}</a></li>
            </ul>
        </div>
    </div>

    <x-bs::card>
        <div class="table-responsive">
            {{--            @foreach($options as $group)--}}
            {{--                <div class="d-flex gap-1">--}}
            {{--                    @foreach($group as $option)--}}
            {{--                        <a href="#">{{ $option }}</a>--}}
            {{--                    @endforeach--}}
            {{--                </div>--}}
            {{--            @endforeach--}}

            @include('eshop::dashboard.variant.partials.variants-table')
        </div>
    </x-bs::card>

    @include('eshop::dashboard.variant.partials.variant-bulk-edit-modal')
    @include('eshop::dashboard.variant.partials.variant-bulk-image-modal')
    @include('eshop::dashboard.variant.partials.variant-bulk-delete-modal')
</div>
