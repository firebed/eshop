@foreach($property->choices as $choice)
    <li>
        <a href="{{ categoryRoute($category, $filters['m'], collect([$choice]), $filters['min_price'], $filters['max_price'], sort: $filters['sort']) }}"
           @class(["filter-option", "filter-radio", "selected" => $filters['c']->contains($choice->id), "disabled" => $choice->products_count === 0])
           @if($choice->products_count === 0) rel="nofollow" @endif
        >
            {{ $choice->name }}
            <small>({{ $choice->products_count }})</small>
        </a>
    </li>
@endforeach
