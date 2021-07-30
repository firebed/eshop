<div class='d-flex flex-column'>
    <div class='d-flex gap-3 mb-3 align-items-baseline'>
        <div>{{ __("eshop::filters.price") }}</div>

        @if (!empty($filters['min_price']) || !empty($filters['max_price']))
            <a href='{{ categoryRoute($category, $filters['m'], $filters['c']) }}' class='small text-hover-underline'>{{ __('eshop::filters.cancel') }}</a>
        @endif
    </div>

    @foreach($priceRanges as $range)
        <a class="filter-option filter-radio @if($filters['min_price'] == $range['min'] && $filters['max_price'] == $range['max']) selected @endif @if($range['products_count'] === 0) disabled @endif" href="{{ categoryRoute($category, $filters['m'], $filters['c'], $range['min'], $range['max']) }}">
            @if ($loop->first)
                {{ __('eshop::filters.price_to') }} {{ format_currency($range['max']) }}
            @elseif($loop->last)
                {{ __('eshop::filters.price_from') }} {{ format_currency($range['min']) }}
            @else
                {{ format_number($range['min'], 2) }} - {{ format_currency($range['max']) }}
            @endif

            <small>({{ $range['products_count'] }})</small>
        </a>
    @endforeach
</div>
