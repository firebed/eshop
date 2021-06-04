<div class="col-12 col-xxl-8 mx-auto p-4 d-grid gap-3">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="fs-3 mb-0">{{ __("New Product Group") }}</h1>

        <x-bs::button.primary wire:click="save" wire:loading.attr="disabled">
            <i wire:loading.remove wire:target="save" class="fa fa-save me-2"></i>
            <i wire:loading wire:target="save" class="fa fa-spinner fa-spin me-2"></i>
            {{ __("Save") }}
        </x-bs::button.primary>
    </div>

    <div class="row">
        <div class="col-7 d-flex flex-column gap-4">
            @include('dashboard.product.partials.primary')
            @include('dashboard.product.partials.create-variant-types')

            <x-bs::card>
                <x-bs::card.body>
                    <div class="fs-5 mb-3">{{ __("Accessibility") }}</div>
                    <x-bs::input.checkbox wire:model.defer="product.visible" id="visible">{{ __('Customers can view this group') }}</x-bs::input.checkbox>
                </x-bs::card.body>
            </x-bs::card>
        </div>
        <div class="col-5 d-flex flex-column gap-4">
            @include('dashboard.product.partials.image')
            @include('dashboard.product.partials.organization')
            @include('dashboard.product.partials.attributes')
        </div>
    </div>
</div>
