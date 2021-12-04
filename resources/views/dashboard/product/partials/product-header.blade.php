<div class="d-grid gap-1">
    @if(request()->routeIs('products.edit'))
        <h1 class="fs-3">{{ $product->name }}</h1>
    @else
        <h1 class="fs-3"><a href="{{ route('products.edit', $product) }}" class="text-decoration-none">{{ $product->name }}</a></h1>
    @endif

    <div id="product-nav" class="d-flex gap-4 flex-nowrap overflow-auto scrollbar-hidden">
        @if(productRouteExists())
            <a href="{{ productRoute($product) }}">{{ __("View") }}</a>
        @endif
        
        @if($product->has_variants)
            <a href="{{ route('products.variants.index', $product) }}" @class(["active" => request()->routeIs('products.variants.*') || request()->routeIs('variants.*')])>{{ __("Variants") }}</a>
        @endif

        <a href="{{ route('products.images.index', $product) }}" @class(["active" => request()->routeIs('products.images.*')])>{{ __("Images") }}</a>

        @can('View carts')
            <a href="{{ route('products.movements.index', $product) }}" @class(["active" => request()->routeIs('products.movements.*')])>{{ __("Movements") }}</a>
        @endcan
    </div>
</div>