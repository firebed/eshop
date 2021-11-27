<div class='d-grid mb-4'>
    <div class='d-flex gap-3 align-items-baseline mb-3'>
        <h3 class="fw-normal" style="font-size: 17px">{{ __("eshop::filters.manufacturers") }}</h3>

        @if ($selectedManufacturers->isNotEmpty())
            <a href='{{ route('products.collections.index', [app()->getLocale(), $collection->slug, 'min_price' => request()->query('min_price'), 'max_price' => request()->query('max_price')]) }}' class='small text-hover-underline'>{{ __('eshop::filters.cancel') }}</a>
        @endif
    </div>

    <ul class="list-unstyled" style="font-size: 15px">
        @foreach($manufacturers as $manufacturer)
            <li>
                <a href="{{ route('products.collections.index', array_filter([app()->getLocale(), $collection->slug, 'manufacturer_ids' => $selectedManufacturers->toggle($manufacturer)->pluck('id')->join('-'), 'min_price' => request()->query('min_price'), 'max_price' => request()->query('max_price')])) }}"
                   @class(["filter-option", "filter-checkbox", "selected" => $selectedManufacturers->contains($manufacturer), "disabled" => $manufacturer->products_count === 0])
                   @if($manufacturer->products_count === 0) rel="nofollow" @endif
                >
                    {{ $manufacturer->name }}
                    <small>({{ $manufacturer->products_count }})</small>
                </a>
            </li>
        @endforeach
    </ul>
</div>
