@if($product->preview_variants)
    <div class="row row-cols-4 gx-1 mt-2">
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