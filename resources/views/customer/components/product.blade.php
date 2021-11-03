<div class="col">
    <div class="card h-100">
        <div class="card-body vstack position-relative">
            <a href="{{ productRoute($product) }}" class="ratio ratio-1x1 mb-3">
                @if($product->image && $src = $product->image->url('sm'))
                    <img loading="lazy" src="{{ $src }}" title="{{ $product->name }}" alt="{{ $product->name }}" class="rounded {{ eshop('product.image.cover') ? '' : 'img-middle' }}">
                @endif
            </a>

            <div class="lh-sm fw-500 mb-3">
                <a class="text-dark text-hover-underline" href="{{ productRoute($product) }}">{{ $product->name }}</a>
            </div>

            <div class="d-flex align-items-baseline fw-bold mt-auto" style="font-size: 1.1rem">
                @if($product->has_variants)
                    @if(($min = $product->variants->min('netValue')) !== ($max = $product->variants->max('netValue')))
                        <div>{{ format_currency($min) }} - {{ format_currency($max) }}</div>
                    @else
                        <div>{{ format_currency($min) }}</div>
                    @endif
                @else
                    <div>{{ format_currency($product->netValue) }}</div>
                @endif

                @if($product->discount > 0)
                    <s class="text-secondary fw-normal small ms-3">{{ format_currency($product->price) }}</s>
                @endif
            </div>

            @if($product->has_variants)
                @if($product->preview_variants && $product->variants->isNotEmpty())
                    <div class="row row-cols-4 gx-1 mt-2 overflow-hidden">
                        @foreach($product->variants->take(4) as $variant)
                            <div class="col">
                                <div class="ratio ratio-1x1">
                                    @if($variant->image && $src = $variant->image->url('sm'))
                                        <img loading="lazy" class="img-middle rounded" src="{{ $src }}" title="{{ $variant->trademark }}" alt="{{ $variant->trademark }}">
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        @if($product->variants->count() - 4 > 0)
                            <div class="col-12 small mt-1 text-secondary">
                                + {{ trans_choice("eshop::product.variants_count", $product->variants->count() - 4, ['count' => $product->variants->count() - 4]) }}
                            </div>
                        @endif
                    </div>
                @else
                    <div class="col-12 small text-secondary">
                        {{ trans_choice("eshop::product.variants_count", $product->variants->count(), ['count' => $product->variants->count()]) }}
                    </div>
                @endif
            @endif

            @if($product->recent)
                <img loading="lazy" src="{{ asset('storage/images/new-ribbon.png') }}" alt="New ribbon" class="position-absolute" style="width: 100px; height: 100px; left: -13px; top: -12px">
            @endif

            @if((!$product->has_variants && $product->discount > 0) || ($product->has_variants && $product->variants->where('discount', '>', 0)->isNotEmpty()))
                <div class="position-absolute fs-6 badge fw-normal bg-yellow-200 text-orange-600" style="right: 1rem">
                    @if($product->has_variants)
                        {{ format_percent(-$product->variants->max('discount')) }}
                    @else
                        {{ format_percent(-$product->discount) }}
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>