<div class="small hstack gap-2">
    <span class="text-secondary">{{ __("Collection") }}:</span>
    <a href="{{ route('products.show', [app()->getLocale(), $category->slug, $product->parent->slug]) }}" class="text-decoration-none">{{ $product->parent->name }}</a>
</div>
