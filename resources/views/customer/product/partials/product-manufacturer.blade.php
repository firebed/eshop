<div class="d-flex">
    <div class="me-3">{{ __("Manufacturer") }}:</div>
    <a href="{{ categoryRoute($category, collect([$product->manufacturer])) }}" class="text-decoration-none">{{ $product->manufacturer->name }}</a>
</div>
