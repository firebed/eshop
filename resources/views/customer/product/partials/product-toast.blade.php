<div class="d-grid gap-2">
    <div class="d-flex gap-1">
        @if($product->image)
            <div class="w-5r ratio ratio-1x1">
                <img src="{{ $product->image->url('sm') }}" alt="{{ $product->trademark }}" class="img-top rounded">
            </div>
        @endif

        <div>{{ __("eshop::cart.added_product") }}</div>
    </div>

    <a href="{{ route('checkout.products.index', app()->getLocale()) }}" class="btn btn-sm btn-outline-primary">{{ __('eshop::cart.see_your_cart') }}</a>
</div>
