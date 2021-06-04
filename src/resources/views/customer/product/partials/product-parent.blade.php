<div>
    <span>{{ __("Collection") }}: </span>
    <a href="{{ route('customer.products.show', [app()->getLocale(), $category->slug, $product->parent->slug]) }}" class="text-decoration-none">{{ $product->parent->name }}</a>
</div>
