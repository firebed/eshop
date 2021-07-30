@foreach($property->choices as $choice)
    <a class="filter-option filter-radio @if($filters['c']->contains($choice->id)) selected @endif @if($choice->products_count === 0) disabled @endif"  href="{{ categoryRoute($category, $filters['m'], collect([$choice]), $filters['min_price'], $filters['max_price']) }}">
        {{ $choice->name }}
        <small>({{ $choice->products_count }})</small>
    </a>
@endforeach