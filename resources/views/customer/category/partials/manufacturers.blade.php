<div class='d-grid mb-5'>
    <div class='d-flex gap-3 align-items-baseline mb-3'>
        <div>{{ __("eshop::filters.manufacturers") }}</div>

        @if ($filters['m']->isNotEmpty())
            <a href='{{ categoryRoute($category, null, $filters['c'], $filters['min_price'], $filters['max_price']) }}' class='small text-hover-underline'>{{ __('eshop::filters.cancel') }}</a>
        @endif
    </div>
    @foreach($manufacturers as $manufacturer)
        <a href="{{ categoryRoute($category, $filters['m']->toggle($manufacturer), $filters['c'], $filters['min_price'], $filters['max_price']) }}"
           @class(["filter-option", "filter-checkbox", "selected" => $filters['m']->contains($manufacturer->id), "disabled" => $manufacturer->products_count === 0])
           @if($manufacturer->products_count === 0) rel="nofollow" @endif
        >
            {{ $manufacturer->name }}
            <small>({{ $manufacturer->products_count }})</small>
        </a>
    @endforeach
</div>
