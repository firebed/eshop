@foreach($property->choices as $choice)
    <a href="{{ categoryRoute($category, $filters['m'], $filters['c']->toggle($choice), $filters['min_price'], $filters['max_price']) }}"
       @class(["filter-option", "filter-checkbox", "filter-color", "light" => in_array($choice->name, config('colors.light')), "selected" => $filters['c']->contains($choice->id), "disabled" => $choice->products_count === 0])
       @if($choice->products_count === 0) rel="nofollow" @endif
    >
        @if($choice->name === 'Πολύχρωμο')
            <div class="filter-indicator" style="background-image:url('{{ asset('images/multicolor.png') }}')"></div>
        @else
            <div class="filter-indicator" style="background-color: {{ config("colors.$choice->name") }}"></div>
        @endif

        {{ $choice->name }}
        <small>({{ $choice->products_count }})</small>
    </a>
@endforeach
