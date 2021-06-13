<div class='d-flex flex-column mb-5'>
    <div class='d-flex flex-column filters'>
        <div class='d-flex mb-3'>
            <div class='h6 mb-0'>{{ __("Price") }}</div>
            @if (!empty($filters['min_price']) || !empty($filters['max_price']))
                <a href='{{ categoryRoute($category, $filters['m'], $filters['c']) }}' class='small ms-3'>{{ __('Clear') }}</a>
            @endif
        </div>

        @foreach($priceRanges as $range)
            <div class="form-check">
                <input autocomplete='off'
                       type="radio"
                       class="form-check-input price"
                       id="p-{{ $loop->index }}"
                       name="price"
                       onchange="location.href = '{{ categoryRoute($category, $filters['m'], $filters['c'], $range['min'], $range['max']) }}'"
                       @if($range['products_count'] === 0) disabled @endif
                       @if($filters['min_price'] == $range['min'] && $filters['max_price'] == $range['max']) checked @endif>

                <label class="form-check-label" for="p-{{ $loop->index }}">
                    @if ($loop->first)
                        {{ __('To') }} {{ format_currency($range['max']) }}
                    @elseif($loop->last)
                        {{ __('From') }} {{ format_currency($range['min']) }}
                    @else
                        {{ format_number($range['min'], 2) }} - {{ format_currency($range['max']) }}
                    @endif
                    <small class="text-secondary">({{ $range['products_count'] }})</small>
                </label>
            </div>
        @endforeach
    </div>
</div>
