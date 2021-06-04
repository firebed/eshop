<div class="col-12 col-xxl-8 mx-auto p-4 d-grid gap-3">
    <div class="d-grid gap-2">
        <a href="{{ route('products.index') }}" class="text-secondary text-decoration-none"><i class="fa fa-chevron-left"></i> {{ __("All products") }}</a>

        <div class="d-flex align-items-center justify-content-between">
            <h1 class="fs-3 mb-0">{{ $product->name }}</h1>

            <x-bs::button.primary wire:click="save" wire:loading.attr="disabled">
                <em wire:loading.remove wire:target="save" class="fa fa-save me-2"></em>
                <em wire:loading wire:target="save" class="fa fa-spinner fa-spin me-2"></em>
                {{ __("Save") }}
            </x-bs::button.primary>
        </div>
    </div>

    <div class="d-flex">
        @if(Route::has('customer.products.show'))
            <a href="{{ route('customer.products.show') }}" class="text-secondary text-decoration-none me-4"><i class="fa fa-eye"></i> {{ __("View") }}</a>
        @endif

        <a href="#" class="text-secondary text-decoration-none me-4"><i class="fa fa-chart-bar"></i> {{ __("Analytics") }}</a>

        @if(Route::has('products.images.index'))
            <a href="{{ route('products.images.index') }}" class="text-secondary text-decoration-none me-4"><i class="far fa-images"></i> {{ __("Images") }}</a>
        @endif
    </div>

    <div class="row">
        <div class="col-7 d-flex flex-column gap-4">
            @include('dashboard.product.partials.primary')
            @include('dashboard.product.partials.pricing')
            @include('dashboard.product.partials.inventory')
            @include('dashboard.product.partials.accessibility')
            @include('dashboard.product.partials.delete-product')
        </div>
        <div class="col-5 d-flex flex-column gap-4">
            @include('dashboard.product.partials.image')
            @include('dashboard.product.partials.organization')
            @include('dashboard.product.partials.attributes')
        </div>
    </div>
</div>
