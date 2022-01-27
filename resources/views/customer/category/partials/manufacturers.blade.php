<div class='d-grid mb-4'>
    <div class='d-flex gap-3 align-items-baseline mb-3'>
        <h3 class="fw-normal" style="font-size: 17px">{{ __("eshop::filters.manufacturers") }}</h3>

        @if($filters['m']->isNotEmpty())
            <a href='{{ categoryRoute($category, null, $filters['c'], $filters['min_price'], $filters['max_price'], sort: $filters['sort']) }}' class='small text-hover-underline'>{{ __('eshop::filters.cancel') }}</a>
        @endif
    </div>

    <ul class="list-unstyled" style="font-size: 15px">
        @foreach($manufacturers as $manufacturer)
            <li>
                <a href="{{ categoryRoute($category, $filters['m']->toggle($manufacturer), $filters['c'], $filters['min_price'], $filters['max_price'], sort: $filters['sort']) }}"
                   @class(["filter-option", "filter-checkbox", "selected" => $filters['m']->contains($manufacturer->id), "disabled" => $manufacturer->products_count === 0])
                   @if($manufacturer->products_count === 0 || (count($filters['m']) + count($filters['c']) >= 4)) rel="nofollow" @endif
                >
                    {{ $manufacturer->name }}
                    <small>({{ $manufacturer->products_count }})</small>
                </a>
            </li>
        @endforeach
    </ul>
</div>
