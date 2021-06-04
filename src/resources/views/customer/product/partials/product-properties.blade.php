<div class="d-grid">
    <h2 class="fs-6 fw-500">{{ __("Attributes") }}</h2>
    <div class="product-description-bullets">
        <ul class="row row-cols-1 row-cols-sm-2 row-cols-md-1 row-cols-xl-2 mb-4 g-1 px-0">
            @foreach($properties as $property)
                <li class="col">
                    @if($property->show_caption)
                        <span class="text-secondary">{{ $property->name }}</span>
                    @endif

                    @if($property->isValueRestricted())
                        <span>{{ $choices->where('pivot.category_property_id', $property->id)->pluck('name')->join(', ') }}</span>
                    @else
                        <span>{{ $property->pivot->value }}</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
