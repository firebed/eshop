<div class="col-12 col-xxl-8 mx-auto p-4 d-grid gap-3">
    <div class="d-grid gap-2">
        <a href="{{ route('products.index') }}" class="text-secondary text-decoration-none"><em class="fa fa-chevron-left"></em> {{ __("All products") }}</a>

        <div class="d-flex align-items-center justify-content-between">
            <h1 class="fs-3 mb-0">{{ $product->name }}</h1>

            <x-bs::button.primary wire:click="save" wire:loading.attr="disabled">
                <em wire:loading.remove wire:target="save" class="fa fa-save me-2"></em>
                <em wire:loading wire:target="save" class="fa fa-spinner fa-spin me-2"></em>
                {{ __("Save") }}
            </x-bs::button.primary>
        </div>

        <div class="d-flex gap-4">
            @if(Route::has('customer.products.show'))
                <a href="{{ route('customer.products.show', [app()->getLocale(), $product->category->slug, $product->slug]) }}" class="text-secondary text-decoration-none"><i class="fa fa-eye"></i> {{ __("View") }}</a>
            @endif

            <a href="#" class="text-secondary text-decoration-none"><em class="fa fa-chart-bar"></em> {{ __("Analytics") }}</a>
            <a href="{{ route('products.variants.index', $product) }}" class="text-secondary text-decoration-none"><em class="fa fa-sitemap"></em> {{ __("Variants") }}</a>

            @if(Route::has('products.images.index'))
                <a href="{{ route('products.images.index', $product) }}" class="text-secondary text-decoration-none"><i class="far fa-images"></i> {{ __("Images") }}</a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-7 d-flex flex-column gap-4">
            @include('dashboard.product.partials.primary')
            @livewire('dashboard.product.variant-types', compact('product'))

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="fs-5 mb-3">{{ __("Accessibility") }}</div>
                    <x-bs::input.checkbox wire:model.defer="product.visible" id="visible">{{ __('Customers can view this group') }}</x-bs::input.checkbox>
                </div>
            </div>

            @include('dashboard.product.partials.delete-product')
        </div>

        <div class="col-5 d-flex flex-column gap-4">
            @include('dashboard.product.partials.image')
            @include('dashboard.product.partials.organization')
            @include('dashboard.product.partials.attributes')
        </div>
    </div>
</div>
