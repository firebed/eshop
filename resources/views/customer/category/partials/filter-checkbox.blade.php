@foreach($property->choices as $choice)
    <a href="{{ categoryRoute($category, $filters['m'], $filters['c']->toggle($choice), $filters['min_price'], $filters['max_price']) }}"
       class="filter-option filter-checkbox @if($filters['c']->contains($choice->id)) selected @endif @if($choice->products_count === 0) disabled @endif"
    >
        {{ $choice->name }}
        <small>({{ $choice->products_count }})</small>
    </a>
@endforeach