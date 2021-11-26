@foreach($category->properties as $property)
    @if($property->choices->isNotEmpty())
        <div class='d-grid gap-1 mb-4'>
            <div class='d-flex gap-3 align-items-baseline mb-3'>
                <h3 class="fw-normal" style="font-size: 17px">{{ $property->name }}</h3>

                @if ($filters['c']->contains('property.id', $property->id))
                    <a href='{{ categoryRoute($category, $filters['m'], $filters['c']->reject(fn($v) => $v->property->id === $property->id), $filters['min_price'], $filters['max_price'], sort: $filters['sort']) }}' class='small text-hover-underline'>{{ __('eshop::filters.cancel') }}</a>
                @endif
            </div>

            <ul class="list-unstyled" style="font-size: 15px">
                @includeWhen($property->isCheckbox() && $property->name !== 'Χρώμα', 'eshop::customer.category.partials.filter-checkbox')
                @includeWhen($property->isCheckbox() && $property->name === 'Χρώμα', 'eshop::customer.category.partials.filter-color')
                @includeWhen($property->isRadio(), 'eshop::customer.category.partials.filter-radio')
            </ul>
        </div>
    @endif
@endforeach
