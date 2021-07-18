<div class="d-grid gap-3">
    <div class="d-flex gap-2">
        <x-bs::input.search wire:model="search" placeholder="{{ __('Search') }}"/>

        <x-bs::dropdown wire:ignore>
            <x-bs::dropdown.button class="btn-white" id="bulk-actions">{{ __("Actions") }}</x-bs::dropdown.button>
            <x-bs::dropdown.menu button="bulk-actions" alignment="right">
                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#mass-variant-image-modal">
                    <em class="fa fa-image me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.mass-actions.image') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.divider/>

                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#mass-variant-property-modal" data-property="price" data-title="{{ __('eshop::variant.mass-actions.price') }}">
                    <em class="fa fa-tag me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.mass-actions.price') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#mass-variant-property-modal" data-property="compare_price" data-title="{{ __('eshop::variant.mass-actions.compare_price') }}">
                    <em class="fa fa-tag me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.mass-actions.compare_price') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#mass-variant-property-modal" data-property="discount" data-title="{{ __('eshop::variant.mass-actions.discount') }}">
                    <em class="fa fa-tag me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.mass-actions.discount') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.divider/>

                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#mass-variant-property-modal" data-property="sku" data-title="{{ __('eshop::variant.mass-actions.sku') }}">
                    <em class="fa fa-tag me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.mass-actions.sku') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#mass-variant-property-modal" data-property="stock" data-title="{{ __('eshop::variant.mass-actions.stock') }}">
                    <em class="fa fa-tag me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.mass-actions.stock') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#mass-variant-property-modal" data-property="weight" data-title="{{ __('eshop::variant.mass-actions.weight') }}">
                    <em class="fa fa-tag me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.mass-actions.weight') }}
                </x-bs::dropdown.item>

                <x-bs::dropdown.divider/>

                <x-bs::dropdown.item data-bs-toggle="modal" data-bs-target="#mass-variant-delete-modal">
                    <em class="far fa-trash-alt me-2 text-secondary w-1r"></em>
                    {{ __('eshop::variant.actions.delete') }}
                </x-bs::dropdown.item>
            </x-bs::dropdown.menu>
        </x-bs::dropdown>
    </div>

    <x-bs::card>
        <div class="table-responsive">
            @include('eshop::dashboard.variant.partials.variants-table')
        </div>
    </x-bs::card>

    @include('eshop::dashboard.variant.partials.mass-variant-image-form')
    @include('eshop::dashboard.variant.partials.mass-variant-property-form')
    @include('eshop::dashboard.variant.partials.mass-variant-delete-form')
</div>
