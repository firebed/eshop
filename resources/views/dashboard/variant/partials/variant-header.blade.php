<div class="d-grid gap-2">
    <a href="{{ route('products.edit', $product) }}" class="text-decoration-none"><em class="fa fa-chevron-left"></em> {{ $product->name }}</a>

    <div class="d-flex align-items-center justify-content-between">
        <h1 class="fs-3 mb-0">{{ __("Variants") }}</h1>

        <div class="d-flex gap-2">
            <a href="{{ route('products.variants.create', $product) }}" class="btn btn-primary">
                <em class="fa fa-plus me-2"></em> {{ __("New") }}
            </a>

            <a href="{{ route('products.variants.mass-create', $product) }}" class="btn btn-primary">
                <em class="fa fa-folder-plus me-2"></em> {{ __("Add many") }}
            </a>
        </div>
    </div>
</div>