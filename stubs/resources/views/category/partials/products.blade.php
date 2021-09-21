@foreach($products as $product)
    <div class="col">
        <div class="card h-100">
            <div class="card-body vstack position-relative">
                <a href="{{ productRoute($product, $category) }}" class="ratio ratio-1x1 mb-3">
                    @if($product->image && $src = $product->image->url('sm'))
                        <img src="{{ $src }}" alt="{{ $product->name }}" class="rounded">
                    @endif
                </a>

                <div class="lh-sm fw-500 mb-3">
                    <a class="text-dark text-hover-underline" href="{{ productRoute($product, $category) }}">{{ $product->name }}</a>
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

                @includeWhen($product->has_variants && $product->variants->isNotEmpty(), 'category.partials.variant-thumbnails')

                @if((!$product->has_variants && $product->discount > 0) || ($product->has_variants && $product->variants->where('discount', '>', 0)->isNotEmpty()))
                    <div class="position-absolute fs-6 badge fw-normal bg-yellow-200 text-orange-600" style="right: 1rem">
                        @unless($product->has_variants)
                            {{ format_percent(-$product->discount) }}
                        @else
                            % {{ __("Offer") }}
                        @endunless
                    </div>
                @endif
            </div>
        </div>
    </div>
@endforeach
