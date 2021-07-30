@foreach($property->choices as $choice)
    @if($choice->products_count === 0)
        <a class="filter-option filter-checkbox disabled @if($filters['c']->contains($choice->id)) selected @endif" href="{{ categoryRoute($category, $filters['m'], $filters['c']->toggle($choice), $filters['min_price'], $filters['max_price']) }}">
            {{ $choice->name }}
            <small>({{ $choice->products_count }})</small>
        </a>
    @else
        <a class="filter-option filter-checkbox @if($filters['c']->contains($choice->id)) selected @endif" href="{{ categoryRoute($category, $filters['m'], $filters['c']->toggle($choice), $filters['min_price'], $filters['max_price']) }}">
            {{ $choice->name }}
            <small>({{ $choice->products_count }})</small>
        </a>
    @endif
@endforeach