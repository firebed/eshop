<div class="alert alert-secondary my-4 p-4 text-center">
    <em class="fas fa-search text-gray-500 fa-3x my-4"></em>
    <div class="h4">{{ __("No products found") }}</div>
    <div class="text-secondary mb-4">{{ __("Try to remove your last selection") }}</div>

    <a href="{{ route('products.offers.index', app()->getLocale()) }}" class="btn btn-primary">{{ __("See all offers") }}</a>
</div>
