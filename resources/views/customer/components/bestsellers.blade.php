<section class="container-fluid py-3 pb-5 bg-white">
    <div class="container-xxl">
        <div class="fw-bold py-5 vstack gap-3">
            <h2 class="mx-auto">Bestsellers</h2>
            <div class="border-bottom border-3 border-primary w-3r mx-auto"></div>
        </div>

        <x-bs::slider slides="1" slides-sm="2" slides-lg="3" slides-xl="5" interval="3500" class="gx-4">
            @foreach($products as $product)
                <x-bs::slider.item class="w-md-50 w-lg-1/3 w-xl-20 pb-1">
                    <div class="vstack h-100 gap-2 justify-content-between">
                        <a href="{{ productRoute($product) }}" title="{{ $product->name }}" class="ratio ratio-1x1">
                            @if($src = $product->image?->url('sm'))
                                <img class="rounded {{ eshop('product.image.cover') ? '' : 'img-middle' }}" src="{{ $src }}" alt="{{ $product->name }}">
                            @endif
                        </a>

                        <div class="fs-6 fw-500"><a href="{{ productRoute($product) }}" title="{{ $product->name }}" class="fw-500 text-decoration-none text-dark">{{ $product->name }}</a></div>
                        <a href="{{ productRoute($product) }}" title="{{ $product->name }}" class="text-decoration-none text-dark mt-auto fw-bold">{{ format_currency($product->net_value) }}</a>
                    </div>
                </x-bs::slider.item>
            @endforeach

            <x-bs::slider.nav/>
        </x-bs::slider>
    </div>
</section>
