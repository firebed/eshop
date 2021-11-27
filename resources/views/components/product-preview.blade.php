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

            <div class="product-price">
                @if($product->has_variants && $product->relationLoaded('variants'))
                    @if($product->variants->contains->isOnSale())
                        <div class="product-discount">
                            {{ format_percent(-$product->variants->max('discount')) }}
                        </div>

                        @if($product->variants->min('netValue') !== $product->variants->min('price'))
                            <del>{{ format_currency($product->variants->min('price')) }}</del>
                        @endif
                    @endif

                    @if(($min = $product->variants->min('netValue')) !== ($max = $product->variants->max('netValue')))
                        <span class="fw-normal small text-secondary">από</span>
                    @endif

                    {{ format_currency($min) }}
                @else
                    @if($product->isOnSale())
                        <div class="product-discount">
                            {{ format_percent(-$product->discount) }}
                        </div>

                        <del class="text-danger small mt-auto">{{ format_currency($product->price) }}</del>
                    @endif

                    {{ format_currency($product->netValue) }}
                @endif
            </div>
        </div>
    </a>
</div>
