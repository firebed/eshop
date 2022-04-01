<div class="d-grid gap-1">
    @if(request()->routeIs('products.edit'))
        <h1 class="fs-3">{{ $product->name }}</h1>
    @else
        <h1 class="fs-3">
            <a href="{{ route('products.edit', $product) }}" class="text-decoration-none">
                <em class="fas fa-chevron-left me-2 small"></em>{{ $product->name }}
            </a>
        </h1>
    @endif

    <div id="product-nav" class="d-flex gap-4 flex-nowrap overflow-auto scrollbar-hidden">
        @if($product->has_variants)
            <a href="{{ route('products.variants.index', $product) }}" @class(["active" => request()->routeIs('products.variants.*') || request()->routeIs('variants.*')])>{{ __("Variants") }}</a>
        @endif

        <a href="{{ route('products.images.index', $product) }}" @class(["active" => request()->routeIs('products.images.*')])>{{ __("Images") }}</a>

        @can('View product movements')
            <a href="{{ route('products.movements.index', $product) }}" @class(["active" => request()->routeIs('products.movements.*')])>{{ __("Movements") }}</a>
        @endcan

        @can('View product audits')
            <a href="{{ route('products.audits.index', $product) }}" @class(["active" => request()->routeIs('products.audits.*')])>{{ __("Audits") }}</a>
        @endcan

        @can('Manage translations')
            <a href="{{ route('products.translations.edit', $product) }}" @class(["active" => request()->routeIs('products.translations.*')])>{{ __("Translations") }}</a>
        @endcan
    </div>
</div>