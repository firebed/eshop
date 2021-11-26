<div class='d-flex flex-column'>
    <div class='d-flex gap-3 mb-3 align-items-baseline'>
        <h3 class="fw-normal" style="font-size: 17px">{{ __("eshop::filters.price") }}</h3>

        @if (request()->filled('min_price') || request()->filled('max_price'))
            <a href='{{ route('products.offers.index', [app()->getLocale(), 'manufacturer_ids' => request()->query('manufacturer_ids')]) }}' class='small text-hover-underline'>{{ __('eshop::filters.cancel') }}</a>
        @endif
    </div>

    <ul class="list-unstyled" style="font-size: 15px">
        @foreach($priceRanges as $range)
            <li>
                <a href="{{ route('products.offers.index', [app()->getLocale(), 'manufacturer_ids' => request()->query('manufacturer_ids'), 'min_price' => $range['min'], 'max_price' => $range['max']]) }}"
                   rel="nofollow"
                    @class(["filter-option", "filter-radio", "selected" => (request()->query('min_price') == $range['min'] && request()->query('max_price') == $range['max']), "disabled" => $range['products_count'] === 0])
                >
                    @if ($loop->first)
                        {{ __('eshop::filters.price_to') }} {{ format_currency($range['max']) }}
                    @elseif($loop->last)
                        {{ __('eshop::filters.price_from') }} {{ format_currency($range['min']) }}
                    @else
                        {{ format_number($range['min'], 2) }} - {{ format_currency($range['max']) }}
                    @endif

                    <small>({{ $range['products_count'] }})</small>
                </a>
            </li>
        @endforeach
    </ul>
</div>
