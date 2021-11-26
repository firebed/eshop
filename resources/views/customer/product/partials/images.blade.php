<div class="row row-cols-1 row-cols-sm-2 gx-4 gy-1"
     x-data="{ show: true, thumbnails: [] }"
     x-on:variant-selected.window="
        thumbnails = $event.detail.images
        $refs.preview.src = $event.detail.image.length > 0 ? $event.detail.image : $refs.preview.src
        $refs.preview.parentElement.href = $event.detail.image
        refreshFsLightbox()
    ">
    <div class="col flex-grow-1 d-grid order-sm-1">
        @if($product->image && $src = $product->image->url())
            <a href="{{ $src }}" class="ratio ratio-4x3" data-fslightbox="images" data-type="image">
                <img x-ref="preview" src="{{ $src }}" alt="{{ $product->trademark }}" class="start-0 top-0 w-auto h-auto mw-100 mh-100 rounded">
            </a>
        @endif
    </div>

    <div class="col w-sm-5r order-sm-0">
        <div class="mh-3r mh-sm-20r scrollbar overflow-auto">
            <ul class="d-flex flex-nowrap d-sm-grid gap-1 list-unstyled">
                @if($product->image && $src = $product->image->url())
                    <li class="d-flex w-3r w-sm-auto">
                        <a x-show="thumbnails.length === 0" href="{{ $src }}" class="ratio ratio-1x1 rounded" data-fslightbox="images" data-type="image">
                            <img src="{{ $product->image->url('sm') }}" alt="{{ $product->trademark }}" class="img-top rounded">
                        </a>
                    </li>
                @endif

                @isset($images)
                    @foreach($images as $image)
                        <li class="d-flex w-3r w-sm-auto">
                            <a x-show="thumbnails.length === 0" href="{{ $image->url() }}" data-fslightbox="images" data-type="image" class="ratio ratio-1x1 rounded">
                                <img src="{{ $image->url('sm') }}" alt="{{ $product->name }}" class="img-top rounded">
                            </a>
                        </li>
                    @endforeach
                @endisset

                <template x-for="thumb in thumbnails" :key="thumb">
                    <li class="d-flex w-3r w-sm-auto">
                        <a x-bind:href="thumb" data-fslightbox="images" data-type="image" class="ratio ratio-1x1 rounded">
                            <img x-bind:src="thumb" alt="{{ $product->name }}" class="img-top rounded">
                        </a>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</div>
