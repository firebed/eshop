@foreach($property->choices as $choice)
    <a href="{{ categoryRoute($category, $filters['m'], $filters['c']->toggle($choice), $filters['min_price'], $filters['max_price'], sort: $filters['sort']) }}"
       @class(["filter-option", "filter-checkbox", "selected" => $filters['c']->contains($choice->id), "disabled" => $choice->products_count === 0])
       @if($choice->products_count === 0) rel="nofollow" @endif
    >
        {{ $choice->name }}
        <small>({{ $choice->products_count }})</small>
    </a>
@endforeach
