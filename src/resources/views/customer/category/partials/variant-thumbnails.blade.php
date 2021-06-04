<div class="row row-cols-4 gx-1">
    @foreach($product->variants->take(4) as $variant)
        <div class="col">
            <div class="ratio ratio-1x1">
                <img src="{{ $variant->image->url('sm') }}" alt="" class="img-middle">
            </div>
        </div>
    @endforeach
    <div class="col-12 small mt-1 text-secondary">
        @if($product->variants->count() - 4 > 0)
            +{{ $product->variants->count() - 4 }} {{ __("options") }}
        @else
            &nbsp;
        @endif
    </div>
</div>
