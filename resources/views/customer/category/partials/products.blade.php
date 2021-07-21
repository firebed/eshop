@foreach($products as $product)
    <div class="col">
        <div class="card h-100 position-relative">
            @if($product->discount > 0)
                <div class="position-absolute p-2 fs-6 badge bg-yellow-500" style="z-index: 2000; top:10px; right: 10px;">{{ format_percent(-$product->discount) }}</div>
            @endif
            <div class="card-body d-flex flex-column gap-3">
                <a href="{{ productRoute($product, $category) }}" class="ratio ratio-4x3">
                    @if($product->image && $src = $product->image->url('sm'))
                        <img class="img-middle" src="{{ $src }}" alt="{{ $product->name }}">
                    @endif
                </a>

                <div class="lh-sm fw-500">
                    <a class="text-dark text-hover-underline" href="{{ productRoute($product, $category) }}">{{ $product->name }}</a>
                </div>

                <div class="d-flex align-items-baseline fw-bold" style="font-size: 1.1rem">
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

                @includeWhen($product->preview_variants && $product->has_variants && $product->variants->isNotEmpty(), 'eshop::customer.category.partials.variant-thumbnails')
            </div>
        </div>
    </div>
@endforeach
