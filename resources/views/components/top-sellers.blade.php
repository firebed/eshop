<div class="border p-3 bg-white">
    <div class="fs-5 mb-3 pb-2 border-bottom">{{ __("Top Sellers") }}</div>

    <x-bs::slider>
        @foreach($products->chunk(4) as $chunk)
            <x-bs::slider.item>
                <div class=" d-grid gap-3">
                    @foreach($chunk as $product)
                        <div class="row gy-3">
                            <div class="col-4">
                                <div class="ratio ratio-1x1 bg-light">
                                    <img class="w-auto h-auto mw-100 mh-100" src="{{ $product->image->url('sm') }}" alt="">
                                </div>
                            </div>
                            <div class="col-8">
                                <h6><a href="#" class="text-decoration-none">{{ $product->name }}</a></h6>
                                <h6 class="fw-light">{{ format_currency($product->price) }}</h6>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-bs::slider.item>
        @endforeach
        <x-bs::slider.nav/>
    </x-bs::slider>
</div>
