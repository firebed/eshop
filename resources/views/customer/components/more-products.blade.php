<h2 class="fs-5 mb-3 pb-2 border-bottom">{{ __("View more") }}</h2>

<ul class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-6 g-3 list-unstyled">
    @foreach($products as $product)
        <li class="col">
            <div @class(["card", "h-100", "new-product" => $product->recent])>
                <div class="card-body vstack position-relative">
                    <a href="{{ productRoute($product) }}" class="ratio ratio-1x1 mb-3">
                        @if($product->image && $src = $product->image->url('sm'))
                            <img loading="lazy" src="{{ $src }}" title="{{ $product->name }}" alt="{{ $product->name }}" class="rounded {{ eshop('product.image.cover') ? '' : 'img-middle' }}">
                        @endif
                    </a>

                    <div class="small text-secondary mb-1">{{ $product->category->name }}</div>

                    @if($product->isVariant())
                        <h3 class="fs-6 fw-500 mb-4">
                            <a class="text-dark text-hover-underline" href="{{ productRoute($product) }}">
                                {{ trim($product->parent->name . ' ' . $product->option_values) }}
                            </a>
                        </h3>
                    @else
                        <h3 class="fs-6 fw-500 mb-4"><a class="text-dark text-hover-underline" href="{{ productRoute($product) }}">{{ $product->name }}</a></h3>
                    @endif

                    <div class="d-flex align-items-baseline mt-auto fw-500">
                        @if($product->has_variants && $product->relationLoaded('variants'))
                            <a href="{{ productRoute($product) }}" class="text-decoration-none text-dark">
                                @if(($min = $product->variants->min('netValue')) !== $product->variants->max('netValue'))
                                    <span class="text-secondary small">από</span>&nbsp;
                                @endif
                                <span class="fw-bold">{{ format_currency($min) }}</span>
                            </a>
                        @else
                            <a href="{{ productRoute($product) }}" class="text-decoration-none text-dark fw-bold">{{ format_currency($product->netValue) }}</a>
                        @endif

                        @if($product->discount > 0)
                            <del class="text-danger small ms-3">{{ format_currency($product->price) }}</del>
                        @endif
                    </div>
                    
                    @if((!$product->has_variants && $product->discount > 0) || ($product->has_variants && $product->relationLoaded('variants') && $product->variants->where('discount', '>', 0)->isNotEmpty()))
                        <div class="product-discount">
                            @if($product->has_variants)
                                {{ format_percent(-$product->variants->max('discount')) }}
                            @else
                                {{ format_percent(-$product->discount) }}
                            @endif
                        </div>
                    @endif
                    
                    @if($product->discount > 0)
                        <div class="product-discount">
                            @if($product->has_variants)
                                {{ format_percent(-$product->variants->max('discount')) }}
                            @else
                                {{ format_percent(-$product->discount) }}
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </li>
    @endforeach
</ul>