<div class="row mt-1">
    <div class="col-2">{{ __("Manufacturer") }}:</div>
    <div class="col">
        <a href="{{ categoryRoute($category, collect([$product->manufacturer])) }}" class="text-decoration-none">{{ $product->manufacturer->name }}</a>
    </div>
</div>
