<div class="d-flex gap-3">
    @if(productRouteExists())
        <a href="{{ productRoute($product) }}" class="text-secondary text-decoration-none"><i class="fa fa-eye"></i> {{ __("View") }}</a>
    @endif

    @if($product->has_variants)
        <a href="{{ route('products.variants.index', $product) }}" class="text-secondary text-decoration-none"><em class="fa fa-sitemap"></em> {{ __("Variants") }}</a>
    @endif

    {{--        <a href="#" class="text-secondary text-decoration-none me-4"><i class="fa fa-chart-bar"></i> {{ __("Analytics") }}</a>--}}

    <a href="{{ route('products.images.index', $product) }}" class="text-secondary text-decoration-none"><i class="far fa-images"></i> {{ __("Images") }}</a>
</div>