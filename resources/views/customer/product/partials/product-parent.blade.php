<div class="small d-flex gap-2" style="font-size: 15px">
    <span class="text-secondary">{{ __("Collection") }}:</span>
    <a href="{{ route('products.show', [app()->getLocale(), $category->slug, $product->parent->slug]) }}" class="text-decoration-none">{{ $product->parent->name }}</a>
</div>
