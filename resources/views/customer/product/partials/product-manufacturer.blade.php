<div class="small hstack gap-2">
    <span class="text-secondary">{{ __("Manufacturer") }}:</span>
    <a href="{{ categoryRoute($category, collect([$product->manufacturer])) }}" class="text-decoration-none">{{ $product->manufacturer->name }}</a>
</div>
