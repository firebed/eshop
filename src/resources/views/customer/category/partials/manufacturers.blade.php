<div class='d-flex flex-column mb-5'>
    <div class='d-flex flex-column filters'>
        <div class='d-flex mb-3'>
            <div class='h6 mb-0'>{{ __("Manufacturers") }}</div>
            @if ($filters['m']->isNotEmpty())
                <a href='{{ categoryRoute($category, NULL, $filters['c'], $filters['min_price'], $filters['max_price']) }}' class='small ms-3'>{{ __('Clear') }}</a>
            @endif
        </div>
        @foreach($manufacturers as $manufacturer)
            <div class="form-check">
                <input type="checkbox"
                       name="manufacturers"
                       class="form-check-input filter"
                       autocomplete='off'
                       id="m-{{ $manufacturer->id }}"
                       @if($filters['m']->contains($manufacturer->id)) checked @endif
                       @if($manufacturer->products_count === 0) disabled @endif
                       onchange="location.href = '{{ categoryRoute($category, $filters['m']->toggle($manufacturer), $filters['c'], $filters['min_price'], $filters['max_price']) }}'">
                <label class="form-check-label" for="m-{{ $manufacturer->id }}">{{ $manufacturer->name }} <small class="text-secondary">({{ $manufacturer->products_count }})</small></label>
            </div>
        @endforeach
    </div>
</div>
