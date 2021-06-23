<div class='row navbar-expand-lg navbar-light flex-wrap mb-3'>
    <div class="container-fluid">
        <button class='btn btn-info d-md-none' type='button' data-bs-toggle='collapse' data-bs-target='#category-filters' aria-controls="category-filters" aria-expanded="false" aria-label="Toggle navigation">
            <span class='fa fa-filter'></span>
        </button>

        <div class='text-left d-lg-block flex-wrap collapse' id="category-filters">
            <div class='d-flex mb-5 align-items-end'>
                <div class='h5 mb-0'>{{ __('eshop::filters.filters') }}</div>
                @if (!empty($filters['min_price']) || !empty($filters['max_price']) || $filters['m']->isNotEmpty() || $filters['c']->isNotEmpty())
                    <a href='{{ categoryRoute($category) }}' class='small ms-3 clear-all'>{{ __('eshop::filters.cancel_all') }}</a>
                @endif
            </div>

            @includeWhen($manufacturers->isNotEmpty(), 'eshop::customer.category.partials.manufacturers')
            @includeWhen($category->properties->isNotEmpty(), 'eshop::customer.category.partials.property-choices')
            @includeWhen(!empty($priceRanges), 'eshop::customer.category.partials.prices')
        </div>
    </div>
</div>