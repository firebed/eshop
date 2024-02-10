@php
    $price = $product->getPriceForUser(auth()->user());
    $netPrice = $product->getNetValueForUser(auth()->user());

    if ($product->has_variants && $product->relationLoaded('variants')) {
        $minNetPrice = $product->variants->min(fn($v) => $v->getNetValueForUser(auth()->user()));
        $maxNetPrice = $product->variants->max(fn($v) => $v->getNetValueForUser(auth()->user()));
        $minPrice = $product->variants->min(fn($v) => $v->getPriceForUser(auth()->user()));
    }
@endphp


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

                        @if(!auth()->user()?->can('Is merchant'))
                            <del>{{ format_currency($minPrice) }}</del>
                        @endif
                    @endif

                    @if($minNetPrice !== $maxNetPrice)
                        <span class="fw-normal small text-secondary">από</span>
                    @endif

                    {{ format_currency($minNetPrice) }}
                @else
                    @if($product->isOnSale())
                        <div class="product-discount">
                            {{ format_percent(-$product->discount) }}
                        </div>

                        <del class="text-danger small mt-auto">{{ format_currency($price) }}</del>
                    @endif

                    {{ format_currency($netPrice) }}
                @endif
            </div>
        </div>
    </a>
</div>
