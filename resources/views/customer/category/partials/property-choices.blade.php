@foreach($category->properties as $property)
    @if($property->choices->isNotEmpty())
        <div class='d-flex flex-column mb-5'>
            <div class='d-flex flex-column filters'>
                <div class='d-flex mb-3'>
                    <div class='h6 mb-0'>{{ $property->name }}</div>
                    @if ($filters['c']->contains('property.id', $property->id))
                        <a href='{{ categoryRoute($category, $filters['m'], $filters['c']->reject(fn($v) => $v->property->id === $property->id), $filters['min_price'], $filters['max_price']) }}' class='small ms-3'>{{ __('eshop::filters.cancel') }}</a>
                    @endif
                </div>
                @foreach($property->choices as $choice)
                    <div class="form-check">
                        <input type="{{ $property->isIndexMultiple() ? 'checkbox' : 'radio' }}"
                               @if(!$property->isIndexMultiple()) name="prop{{ $property->id }}" @endif
                               class="form-check-input filter"
                               autocomplete='off'
                               id="f-{{ $choice->id }}"
                               @if($filters['c']->contains($choice->id)) checked @endif
                               @if($choice->products_count === 0) disabled @endif
                               @if($property->isIndexMultiple())
                                onchange="location.href = '{{ categoryRoute($category, $filters['m'], $filters['c']->toggle($choice), $filters['min_price'], $filters['max_price']) }}'"
                               @else
                                onchange="location.href = '{{ categoryRoute($category, $filters['m'], collect([$choice]), $filters['min_price'], $filters['max_price']) }}'"
                               @endif

                                @if($property->name === 'Χρώμα') style="background-color: {{ config("colors.$choice->name") }}" @endif
                        >
                        <label class="form-check-label" for="f-{{ $choice->id }}">{{ $choice->name }} <small class="text-secondary">({{ $choice->products_count }})</small></label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endforeach