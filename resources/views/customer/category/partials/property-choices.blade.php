@foreach($category->properties as $property)
    @if($property->choices->isNotEmpty())
        <div class='d-grid gap-1 mb-5'>
            <div class='d-flex gap-3 align-items-baseline mb-3'>
                <div>{{ $property->name }}</div>

                @if ($filters['c']->contains('property.id', $property->id))
                    <a href='{{ categoryRoute($category, $filters['m'], $filters['c']->reject(fn($v) => $v->property->id === $property->id), $filters['min_price'], $filters['max_price']) }}' class='small text-hover-underline'>{{ __('eshop::filters.cancel') }}</a>
                @endif
            </div>

            @includeWhen($property->isCheckbox() && $property->name !== 'Χρώμα', 'eshop::customer.category.partials.filter-checkbox')
            @includeWhen($property->isCheckbox() && $property->name === 'Χρώμα', 'eshop::customer.category.partials.filter-color')
            @includeWhen($property->isRadio(), 'eshop::customer.category.partials.filter-radio')
        </div>
    @endif
@endforeach