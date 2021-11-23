<div @class(["card", "h-100", "product-preview", "new-product" => $product->recent])>
    <a href="{{ productRoute($product) }}" title="{{ $product->name }}" class="card-body text-decoration-none text-dark">
        <div class="vstack gap-1 h-100">
            <div class="ratio ratio-4x3 mb-3">
                @if($src = $product->image?->url('sm'))
                    <img loading="lazy" class="rounded {{ eshop('product.image.cover') ? '' : 'img-middle' }}" src="{{ $src }}" alt="{{ $product->name }}">
                @endif
            </div>

            <div class="text-secondary text-truncate" style="font-size:13px">{{ $product->category->name }}</div>
            <h3 class="small fw-500 text-2l lh-base">{{ $product->trademark }}</h3>

            @if($product->isOnSale())
                <del class="text-danger small mt-auto">{{ format_currency($product->price) }}</del>
            @endif

            <div class="product-price">{{ format_currency($product->net_value) }}</div>

            @if($product->isOnSale())
                <div class="position-absolute fs-6 badge fw-normal bg-yellow-200 text-orange-600" style="top: 1rem; right: 1rem">
                    {{ format_percent(-$product->discount) }}
                </div>
            @endif
        </div>
    </a>
</div>
