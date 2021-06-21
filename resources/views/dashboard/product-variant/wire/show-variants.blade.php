<div class="col-12 col-xxl-8 mx-auto p-4 d-grid gap-3">
    <div class="d-grid gap-2">
        <a href="{{ route('products.edit', $product) }}" class="text-decoration-none"><em class="fa fa-chevron-left"></em> {{ $product->name }}</a>

        <h1 class="fs-3 mb-0">{{ __("Variants") }}</h1>
    </div>

    <div class="d-flex justify-content-between">
        <div>
            <x-bs::input.search wire:model="search" placeholder="{{ __('Search') }}"/>
        </div>

        <div class="d-flex gap-2">
            <x-bs::dropdown>
                <x-bs::dropdown.button class="btn-white" id="bulk-actions">{{ __("Actions") }}</x-bs::dropdown.button>
                <x-bs::dropdown.menu button="bulk-actions" alignment="right">
                    <x-bs::dropdown.item wire:click.prevent="showPriceModal()">
                        <em class="fa fa-tag me-2 text-secondary w-1r"></em>
                        {{ __('Change price') }}
                    </x-bs::dropdown.item>

                    <x-bs::dropdown.item wire:click.prevent="showDiscountModal()">
                        <em class="fa fa-percentage me-2 text-secondary w-1r"></em>
                        {{ __('Change discount') }}
                    </x-bs::dropdown.item>

                    <x-bs::dropdown.divider/>

                    <x-bs::dropdown.item wire:click.prevent="confirmDelete()">
                        <em class="far fa-trash-alt me-2 text-secondary w-1r"></em>
                        {{ __('Delete') }}
                    </x-bs::dropdown.item>
                </x-bs::dropdown.menu>
            </x-bs::dropdown>

            <x-bs::button.primary wire:click="create" wire:loading.attr="disabled" wire:target="create" class="text-nowrap">
                <em class="fa fa-plus me-2"></em> {{ __("New") }}
            </x-bs::button.primary>
        </div>
    </div>

    <x-bs::card>
        @include('eshop::dashboard.product-variant.partials.variants-table')
    </x-bs::card>

    @include('eshop::dashboard.product-variant.partials.variants-discount-modal')
    @include('eshop::dashboard.product-variant.partials.variants-price-modal')
    @include('eshop::dashboard.product-variant.partials.delete-variant')
    @include('eshop::dashboard.product-variant.partials.variant-modal')
</div>
