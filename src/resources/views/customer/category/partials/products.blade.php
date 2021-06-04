@foreach($products as $product)
    <div class="col">
        <a class="text-decoration-none text-dark" href="{{ route('customer.products.show', [app()->getLocale(), $category->slug, $product->slug]) }}">
            <div class="card h-100 position-relative">
                @if($product->discount > 0)
                    <div class="position-absolute p-2 fs-6 badge bg-yellow-500" style="z-index: 2000; top:10px; right: 10px;">{{ format_percent(-$product->discount) }}</div>
                @endif
                <div class="card-body d-flex flex-column gap-3">
                    <div class="ratio ratio-4x3">
                        <img class="img-middle" src="{{ $product->image->url('sm') }}" alt="{{ $product->name }}">
                    </div>
                    <div class="lh-sm fw-500">
                        {{ $product->name }}
                    </div>
                    <div class="d-flex align-items-baseline fw-bold @if($product->has_variants) mt-auto @endif" style="font-size: 1.1rem">
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
                            <s class="text-secondary small ms-3">{{ format_currency($product->price) }}</s>
                        @endif
                    </div>

                    @includeWhen($product->has_variants, 'customer.category.partials.variant-thumbnails')
                </div>
            </div>
        </a>
    </div>
@endforeach
