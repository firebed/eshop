<div class="row row-cols-2 g-4">
    <div class="col flex-grow-1 d-grid order-md-1">
        @if($product->image && $src = $product->image->url())
            <a href="{{ $src }}" class="ratio ratio-4x3" data-fslightbox="images" data-type="image">
                <img x-ref="preview" src="{{ $src }}" alt="{{ $product->trademark }}" class="start-0 top-0 w-auto h-auto mw-100 mh-100 rounded">
            </a>
        @endif
    </div>

    @isset($images)
        <div class="col w-md-5r order-md-0">
            <div data-simplebar class="mh-md-17r mh-xl-20r">
                <div class="d-flex d-md-grid gap-1">
                    @foreach($images as $image)
                        <a href="{{ $image->url() }}" data-fslightbox="images" data-type="image">
                            <div class="ratio ratio-1x1 rounded border">
                                <img src="{{ $image->url('sm') }}" alt="{{ $product->name }}" class="img-top rounded">
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endisset
</div>

<div class="small text-orange-500 mt-3">
    <em class="fa fa-exclamation-circle"></em>
    {{ __("The colors of the photos may differ from the actual colors of the products.") }}
</div>
