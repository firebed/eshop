<div class="row row-cols-2 g-4"
     x-data="{ show: true, thumbnails: [] }"
     x-on:variant-selected.window="
        thumbnails = $event.detail.images
        $refs.preview.src = $event.detail.image
        $refs.preview.parentElement.href = $event.detail.image
        refreshFsLightbox()
    ">
    <div class="col flex-grow-1 d-grid order-md-1">
        @if($product->image && $src = $product->image->url())
            <a href="{{ $src }}" class="ratio ratio-1x1" data-fslightbox="images" data-type="image">
                <img x-ref="preview" src="{{ $src }}" alt="{{ $product->trademark }}" class="start-0 top-0 w-auto h-auto mw-100 mh-100 rounded">
            </a>
        @endif
    </div>

    <div class="col w-md-5r order-md-0">
        <div data-simplebar class="mh-md-17r mh-xl-20r">
            <div class="d-flex d-md-grid gap-1">
                @forelse($images as $image)
                    <a x-show="thumbnails.length === 0" href="{{ $image->url() }}" data-fslightbox="images" data-type="image" class="ratio ratio-1x1 rounded border">
                        <img src="{{ $image->url('sm') }}" alt="{{ $product->name }}" class="img-top rounded">
                    </a>
                @empty
                @endforelse

                <template x-for="thumb in thumbnails" :key="thumb">
                    <a x-bind:href="thumb" data-fslightbox="images" data-type="image" class="ratio ratio-1x1 rounded border">
                        <img x-bind:src="thumb" alt="{{ $product->name }}" class="img-top rounded">
                    </a>
                </template>
            </div>
        </div>
    </div>
</div>

<div class="small text-orange-500 mt-3">
    <em class="fa fa-exclamation-circle"></em>
    {{ __("eshop::product.image_color_diff") }}
</div>
