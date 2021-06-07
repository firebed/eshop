<div class="row row-cols-4 gx-1">
    @foreach($product->variants->take(4) as $variant)
        <div class="col">
            <div class="ratio ratio-1x1">
                @if($variant->image && $src = $variant->image->url('sm'))
                    <img class="img-middle" src="{{ $src }}" alt="{{ $variant->trademark }}">
                @endif
            </div>
        </div>
    @endforeach

    @if($product->variants->count() - 4 > 0)
        <div class="col-12 small mt-1 text-secondary">
            + {{ $product->variants->count() }} {{ __("options") }}
        </div>
    @endif
</div>
