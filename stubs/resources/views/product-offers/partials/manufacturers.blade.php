<div class='d-grid mb-5'>
    <div class='d-flex gap-3 align-items-baseline mb-3'>
        <div>{{ __("eshop::filters.manufacturers") }}</div>

        @if ($selectedManufacturers->isNotEmpty())
            <a href='{{ route('products.search.index', [app()->getLocale(), 'min_price' => request()->query('min_price'), 'max_price' => request()->query('max_price')]) }}' class='small text-hover-underline'>{{ __('eshop::filters.cancel') }}</a>
        @endif
    </div>

    @foreach($manufacturers as $manufacturer)
        <a href="{{ route('products.search.index', [app()->getLocale(), $selectedManufacturers->toggle($manufacturer), 'min_price' => request()->query('min_price'), 'max_price' => request()->query('max_price')]) }}"
           class="filter-option filter-checkbox @if($filters['m']->contains($manufacturer->id)) selected @endif @if($manufacturer->products_count === 0) disabled @endif"
        >
            {{ $manufacturer->name }}
            <small>({{ $manufacturer->products_count }})</small>
        </a>
    @endforeach
</div>
