<div class="d-grid gap-2">
    <div class="d-flex gap-1">
        @if($product->image)
            <div class="w-5r ratio ratio-1x1">
                <img src="{{ $product->image->url('sm') }}" alt="{{ $product->tradeName }}" class="img-middle">
            </div>
        @endif

        <div>{{ __("The product was added to cart") }}</div>
    </div>
</div>
