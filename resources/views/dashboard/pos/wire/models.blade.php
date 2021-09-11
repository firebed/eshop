<div class="col-12 vstack gap-3">
    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 row-cols-xxl-5 g-3 small">
        @include("eshop::dashboard.pos.partials.pos-navigation")
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 row-cols-xxl-5 g-3">
        @if(isset($categories))
            @include("eshop::dashboard.pos.partials.pos-categories")
        @elseif(isset($products))
            @include("eshop::dashboard.pos.partials.pos-products")
        @elseif(isset($variants))
{{--            <div class="col-12 fs-5 fw-500">{{ $product->name }}</div>--}}

            @include("eshop::dashboard.pos.partials.pos-variants")
        @endif
    </div>
</div>