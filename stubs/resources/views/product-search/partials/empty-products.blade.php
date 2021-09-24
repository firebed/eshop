<div class="alert alert-secondary my-4 p-4 text-center">
    <em class="fas fa-search text-gray-500 fa-3x my-4"></em>
    <div class="h4">{{ __("No products found") }}</div>
    <div class="text-secondary mb-4">{{ __("Try to remove your last selection") }}</div>

    <a href="{{ route('products.search.index', array_filter([app()->getLocale(), 'search_term' => request()->query('search_term')])) }}" class="btn btn-primary">{{ __("See all search products") }}</a>
</div>
