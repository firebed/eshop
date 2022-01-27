@foreach($property->choices as $choice)
    <li>
        <a href="{{ categoryRoute($category, $filters['m'], $filters['c']->toggle($choice), $filters['min_price'], $filters['max_price'], sort: $filters['sort']) }}"
           @class(["filter-option", "filter-checkbox", "selected" => $filters['c']->contains($choice->id), "disabled" => $choice->products_count === 0])
           @if($choice->products_count === 0 || (count($filters['m']) + count($filters['c']) >= 4)) rel="nofollow" @endif
        >
            {{ $choice->name }}
            <small>({{ $choice->products_count }})</small>
        </a>
    </li>
@endforeach
