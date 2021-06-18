<div class="col-12 col-xxl-8 mx-auto p-4 d-grid gap-3">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="fs-3 mb-0">{{ __("New product group") }}</h1>

        <x-bs::button.primary wire:click="save" wire:loading.attr="disabled">
            <i wire:loading.remove wire:target="save" class="fa fa-save me-2"></i>
            <i wire:loading wire:target="save" class="fa fa-spinner fa-spin me-2"></i>
            {{ __("Save") }}
        </x-bs::button.primary>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-7 d-flex flex-column gap-4">
            @include('eshop::dashboard.product.partials.primary')
            @include('eshop::dashboard.product.partials.create-variant-types')
            @include('eshop::dashboard.product.partials.group-accessibility')
        </div>
        <div class="col-12 col-md-5 d-flex flex-column gap-4">
            @include('eshop::dashboard.product.partials.image')
            @include('eshop::dashboard.product.partials.organization')
            @include('eshop::dashboard.product.partials.attributes')
        </div>
    </div>
</div>
