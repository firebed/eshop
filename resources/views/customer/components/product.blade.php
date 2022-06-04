<li class="col">
    <div @class(["card", "h-100", "new-product" => $product->recent])>
        <div class="card-body vstack position-relative">
            <a href="{{ productRoute($product) }}" class="ratio ratio-1x1 mb-3">
                @if($product->image && $src = $product->image->url('sm'))
                    <img loading="lazy" src="{{ $src }}" title="{{ $product->name }}" alt="{{ $product->name }}" class="rounded {{ eshop('product.image.cover') ? '' : 'img-middle' }}">
                @endif
            </a>

            <h2 class="fs-6 fw-500 mb-4"><a class="text-dark text-hover-underline" href="{{ productRoute($product) }}">{{ $product->name }}</a></h2>

{{--            @if($product->relationLoaded('choices') && $product->choices->isNotEmpty())--}}
{{--                <div class="text-secondary small mt-2 mb-3">--}}
{{--                    {{ $product->choices->map(fn($choice) => ($choice->property->name . ': ' . $choice->name))->join(', ') }}--}}
{{--                </div>--}}
{{--            @endif--}}

            <div class="d-flex align-items-baseline mt-auto" style="font-size: 1.1rem">
                @if($product->has_variants && $product->relationLoaded('variants'))
                    <a href="{{ productRoute($product) }}" class="text-decoration-none">
                        @if(($min = $product->variants->min('netValue')) !== $product->variants->max('netValue'))
                            <span class="text-secondary small">από</span>&nbsp;
                        @endif
                        <span class="fw-bold">{{ format_currency($min) }}</span>
                    </a>
                @else
                    <a href="{{ productRoute($product) }}" class="text-decoration-none">{{ format_currency($product->netValue) }}</a>
                @endif

                @if($product->discount > 0)
                    <del class="text-danger small ms-3">{{ format_currency($product->price) }}</del>
                @endif
            </div>

            @if($product->has_variants && $product->preview_variants && $product->relationLoaded('variants') && $product->variants->isNotEmpty())
                <ul class="row row-cols-4 gx-1 mt-2 overflow-hidden list-unstyled">
                    @foreach($product->variants->take(4) as $variant)
                        <li class="col">
                            <div class="ratio ratio-1x1">
                                @if($variant->image && $src = $variant->image->url('sm'))
                                    <img loading="lazy" class="img-middle rounded" src="{{ $src }}" title="{{ trim($product->name . ' ' . $variant->option_values) }}" alt="{{ trim($product->name . ' ' . $variant->option_values) }}">
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif

            @if((!$product->has_variants && $product->discount > 0) || ($product->has_variants && $product->relationLoaded('variants') && $product->variants->where('discount', '>', 0)->isNotEmpty()))
                <div class="product-discount">
                    @if($product->has_variants)
                        {{ format_percent(-$product->variants->max('discount')) }}
                    @else
                        {{ format_percent(-$product->discount) }}
                    @endif
                </div>
            @endif

            @if($product->has_variants && $product->variants->count() > 0)
                <div class="position-absolute bg-gray-100 rounded px-2 py-1" style="bottom: 1rem; right: 1rem">
                    <div class="color-wheel text-secondary" style="font-size: 12px">
                        &nbsp;{{ $product->variants->count() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</li>