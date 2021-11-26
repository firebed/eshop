<div class='d-grid gap-2 mb-4 border-bottom'>
    <h3 class="fw-normal mb-3" style="font-size: 17px">{{ __("eshop::filters.categories") }}</h3>

    <ul class="vstack overflow-auto scrollbar gap-2 list-unstyled" style="max-height: 15rem; font-size: 15px">
        @foreach($categories as $category)
            <li>
                <a href="{{ categoryRoute($category) }}" class="d-block text-hover-underline text-gray-700 text-truncate">
                    {{ $category->name }}
                    <small>({{ $category->products_count }})</small>
                </a>
            </li>
        @endforeach
    </ul>
</div>
