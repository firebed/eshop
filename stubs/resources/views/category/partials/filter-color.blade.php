@foreach($property->choices as $choice)
    <a href="{{ categoryRoute($category, $filters['m'], $filters['c']->toggle($choice), $filters['min_price'], $filters['max_price']) }}"
       class="filter-option filter-checkbox filter-color @if(in_array($choice->name, config('colors.light'))) light @endif @if($filters['c']->contains($choice->id)) selected @endif @if($choice->products_count === 0) disabled @endif"
   >
        @if($choice->name === 'Πολύχρωμο')
            <div class="filter-indicator" style="background-image:url('{{ asset('storage/images/multicolor.png') }}')"></div>
        @else
            <div class="filter-indicator" style="background-color: {{ config("colors.$choice->name") }}"></div>
        @endif

        {{ $choice->name }}
        <small>({{ $choice->products_count }})</small>
    </a>
@endforeach