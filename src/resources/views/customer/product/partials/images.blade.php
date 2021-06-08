<div class="row align-items-xl-center">
    <div class="col-12 col-md d-grid order-md-1">
        <a href="{{ $product->image->url() }}" class="ratio ratio-4x3" data-fslightbox="images" data-type="image">
            <img x-ref="preview" src="{{ $product->image->url() }}" alt="{{ $product->name }}" class="img-middle rounded">
        </a>
    </div>

    @isset($images)
        <div class="col-12 col-md-auto w-100 w-md-5r order-md-0">
            <div data-simplebar class="mh-md-17r mh-xl-20r">
                <div class="d-flex d-md-grid gap-1">
                    @foreach($images as $image)
                        <a href="{{ $image->url() }}" data-fslightbox="images" data-type="image" class="col-auto w-3r">
                            <div class="ratio ratio-1x1 rounded border">
                                <img src="{{ $image->url('sm') }}" alt="{{ $product->name }}" class="img-middle rounded">
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
