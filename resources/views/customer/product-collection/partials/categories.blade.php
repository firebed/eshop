<div class='d-grid gap-2 mb-5'>
    <div class='mb-3 fs-5'>{{ __("eshop::filters.categories") }}</div>

    <div class="vstack overflow-auto scrollbar gap-2" style="max-height: 15rem">
        @foreach($categories as $category)
            <a href="{{ categoryRoute($category) }}" class="text-hover-underline text-gray-700 small">
                {{ $category->name }}
                <small>({{ $category->products_count }})</small>
            </a>
        @endforeach
    </div>
</div>
