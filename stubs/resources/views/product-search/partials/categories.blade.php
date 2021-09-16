<div class='d-grid gap-1 mb-5'>
    <div class='mb-3 fs-5'>{{ __("eshop::filters.categories") }}</div>

    @foreach($categories as $category)
        <a href="{{ categoryRoute($category) }}" class="text-decoration-none text-dark">
            {{ $category->name }}
            <small>({{ $category->products_count }})</small>
        </a>
    @endforeach
</div>
