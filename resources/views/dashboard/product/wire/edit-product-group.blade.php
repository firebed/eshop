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
            @if(productRouteExists())
                <a href="{{ productRoute($product) }}" class="text-secondary text-decoration-none"><i class="fa fa-eye"></i> {{ __("View") }}</a>
            @endif

            <a href="#" class="text-secondary text-decoration-none"><em class="fa fa-chart-bar"></em> {{ __("Analytics") }}</a>
            <a href="{{ route('products.variants.index', $product) }}" class="text-secondary text-decoration-none"><em class="fa fa-sitemap"></em> {{ __("Variants") }}</a>

            <a href="{{ route('products.images.index', $product) }}" class="text-secondary text-decoration-none"><i class="far fa-images"></i> {{ __("Images") }}</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-7 d-flex flex-column gap-4">
            @include('eshop::dashboard.product.partials.primary')
            <livewire:dashboard.product.variant-types :product="$product"/>

            @include('eshop::dashboard.product.partials.group-accessibility')

            @include('eshop::dashboard.product.partials.delete-product')
        </div>

        <div class="col-12 col-md-5 d-flex flex-column gap-4">
            @include('eshop::dashboard.product.partials.image')
            @include('eshop::dashboard.product.partials.organization')
            @include('eshop::dashboard.product.partials.attributes')
        </div>
    </div>
</div>
