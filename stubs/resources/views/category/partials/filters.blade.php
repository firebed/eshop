<div class="filters offcanvas offcanvas-start" tabindex="-1" id="filters" aria-labelledby="filters">
    <div class="offcanvas-header">
        <div class="d-flex align-items-baseline gap-3">
            <div class="h5 mb-0 offcanvas-title">{{ __('eshop::product.filters') }}</div>

            @if (!empty($filters['min_price']) || !empty($filters['max_price']) || $filters['m']->isNotEmpty() || $filters['c']->isNotEmpty())
                <a href="{{ categoryRoute($category) }}" class="text-hover-underline">
                    {{ __('eshop::filters.cancel_all') }}
                </a>
            @endif
        </div>

        <button type="button" class="d-lg-none btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
        @includeWhen($manufacturers->isNotEmpty(), 'category.partials.manufacturers')
        @includeWhen($category->properties->isNotEmpty(), 'category.partials.property-choices')
        @includeWhen(!empty($priceRanges), 'category.partials.prices')
    </div>

    <div class="offcanvas-header offcanvas-footer d-sm-none">
        <button class="btn btn-primary w-100" data-bs-dismiss="offcanvas" aria-label="Close">
            @choice("eshop::product.products_count", $products->total(), ['count' => $products->total()])
        </button>
    </div>
</div>

<button class="d-lg-none btn btn-primary position-fixed rounded-pill" data-bs-toggle="offcanvas" data-bs-target="#filters" style="bottom: 10px; right: 10px; z-index: 1040">
    <em class="fas fa-sliders-h me-2"></em> {{ __('eshop::product.filters') }}
</button>